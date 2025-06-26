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



class AuthController extends Controller
{

    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Varsayılan olarak null
        $communityId = null;

        // Eğer community_code varsa, var olan topluluğu bul
        if (!empty($request['community_code'])) {
            $community = Community::where('code', $validated['community_code'])->first();

            // Topluluk bulunamazsa validation hatası
            if (!$community) {
                return response()->json(['message' => 'Topluluk bulunamadı.'], 422);
            }

            $communityId = $community->id;

        // Eğer community_name varsa, yeni topluluk oluştur
        } elseif (!empty($request->community_name)) {
            // Generate a 6-character unique code
            $uniqueCode = null;
            do {
                $uniqueCode = Str::upper(Str::random(6)); // Generate 6-character random code (uppercase)
            } while (Community::where('code', $uniqueCode)->exists()); // Check if code already exists

            // Kullanıcıyı önce oluştur, çünkü user_id gerekiyor
            $user = User::create([
                'name' => 'Agalık' . rand(10000, 99999),
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ]);

            // Yeni topluluğu oluştur ve user_id'yi ekle
            $newCommunity = Community::create([
                'name' => $request->community_name,
                'code' => $uniqueCode,
                'user_id' => $user->id, // Store the ID of the user who created the community
            ]);

            $communityId = $newCommunity->id;

            // Kullanıcıya community_id'yi ekle
            $user->update(['community_id' => $communityId]);
        } else {
            return response()->json(['message' => 'Topluluk bilgisi eksik.'], 422);
        }

        // Eğer topluluğa katılınıyorsa, kullanıcıyı oluştur
        if (!isset($user)) {
            $user = User::create([
                'name' => 'Agalık' . rand(10000, 99999),
                'email' => $validated['email'],
                'username' => $validated['username'],
                'community_id' => $communityId,
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Token oluştur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
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


}
