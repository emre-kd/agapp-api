<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Cache;




class AuthController extends Controller
{

    public function register(StoreUserRequest $request){
       $validated = $request->validated();

    // Generate a 6-digit verification code
    $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store verification data in cache
    $registrationData = [
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'community_code' => $request->community_code ?? null,
        'community_name' => $request->community_name ?? null,
        'verification_code' => $verificationCode,
        'created_at' => now(),
    ];

    // Store in cache (expires in 15 minutes)
    $cacheKey = 'reg_verification:'.$validated['email'];
    Cache::put($cacheKey, $registrationData, now()->addMinutes(15));

    // Send verification email
    Mail::to($validated['email'])->send(new VerificationEmail($verificationCode));

    return response()->json([
        'message' => 'Verification code sent to your email',
        'requires_verification' => true,
    ], 200);
    }

    public function login(Request $request){

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function updateUser(Request $request){

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'email' => 'required|string|max:50|unique:users,email,' . $user->id,
            'username' => 'required|string|max:20|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }


        if ($request->hasFile('image')) {

            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/profile_images'), $imageName);
            $user->image = 'uploads/profile_images/' . $imageName;
        }


        if ($request->hasFile('coverImage')) {

            if ($user->coverImage && file_exists(public_path($user->coverImage))) {
                unlink(public_path($user->coverImage));
            }
            $coverImage = $request->file('coverImage');
            $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
            $coverImage->move(public_path('uploads/cover_images'), $coverImageName);
            $user->coverImage = 'uploads/cover_images/' . $coverImageName;
        }


        $user->save();


        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }

    public function getUser(){

        $user = Auth::user();

        if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'id' => $user->id ?? null,
            'community_id' => $user->community_id ?? null,
            'name' => $user->name ?? null,
            'username' => $user->username ?? null,
            'email' => $user->email ?? null,
            'created_at' => $user->created_at ? $user->created_at->toDateTimeString() : null,
            'image' => $user->image ?? null,
            'coverImage' => $user->coverImage ?? null,
            'community' => $user->community ? [
                    'id' => $user->community->id,
                    'code' => $user->community->code,
                    'name' => $user->community->name,
                ] : null,
        ]);

    }

     public function getSearchedUser($id){

        $currentUser = Auth::user();

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = User::with('community')
            ->where('community_id', $currentUser->community_id)
            ->orderBy('created_at', 'desc')
            ->findOrFail($id);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'community_id' => $user->community_id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'coverImage' => $user->coverImage,
                'image' => $user->image,
                'created_at' => $user->created_at->toIso8601String(),
                'community' => $user->community ? [
                    'id' => $user->community->id,
                    'code' => $user->community->code,
                    'name' => $user->community->name,
                ] : null,

            ]
        ], 200);
    }

    public function getUsers() {

        $currentUser = Auth::user();

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $users = User::with('community')
            ->where('community_id', $currentUser->community_id)
            ->where('id', '!=', $currentUser->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'community_id' => $user->community_id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'coverImage' => $user->coverImage,
                    'image' => $user->image,
                    'created_at' => $user->created_at->diffForHumans(),
                    'community' => [
                        'id' => $user->community->id,
                        'code' => $user->community->code,
                        'name' => $user->community->name,
                    ],
                ];
            });

        return response()->json(['users' => $users], 200);
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function verifyRegistration(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
            'email' => 'required|email',
        ]);

        $cacheKey = 'reg_verification:'.$request->email;
        $pendingRegistration = Cache::get($cacheKey);

        // Add proper null checks
        if (!$pendingRegistration || !isset($pendingRegistration['verification_code'])) {
            return response()->json([
                'message' => 'Verification session expired or invalid. Please register again.'
            ], 422);
        }

        if ($request->code !== $pendingRegistration['verification_code']) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 422);
        }

        // Proceed with registration
        $communityId = null;

        if (!empty($pendingRegistration['community_code'])) {
            $community = Community::where('code', $pendingRegistration['community_code'])->first();
            if (!$community) {
                return response()->json(['message' => 'Community not found'], 422);
            }
            $communityId = $community->id;
        } elseif (!empty($pendingRegistration['community_name'])) {
            $uniqueCode = Str::upper(Str::random(6));
            $newCommunity = Community::create([
                'name' => $pendingRegistration['community_name'],
                'code' => $uniqueCode,
                'user_id' => null, // Will update after user creation
            ]);
            $communityId = $newCommunity->id;
        }

        // Create user
        $user = User::create([
            'name' => 'Agalık' . rand(1000, 9999),
            'email' => $pendingRegistration['email'],
            'username' => $pendingRegistration['username'],
            'community_id' => $communityId,
            'password' => Hash::make($pendingRegistration['password']),
            'email_verified_at' => now(),
        ]);

        // If new community, update creator
        if (!empty($pendingRegistration['community_name'])) {
            $newCommunity->update(['user_id' => $user->id]);
        }

        // Clear cache
        Cache::forget($cacheKey);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function resendVerification(Request $request)
    {

    $request->validate([
        'email' => 'required|email',
    ]);

    $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    $cacheKey = 'reg_verification:' . $request->email;

    // Kullanıcı doğrulama verisi geçici olarak cache'e kaydedilir
    Cache::put($cacheKey, [
        'email' => $request->email,
        'verification_code' => $verificationCode,
        // Ek olarak username, password, community vs. de koyabilirsin
    ], now()->addMinutes(15));

    // Doğrulama e-postası gönder
    Mail::to($request->email)->send(new VerificationEmail($verificationCode));

    return response()->json([
        'message' => 'Doğrulama kodu e-posta adresinize gönderildi.',
    ], 200);
    }

}
