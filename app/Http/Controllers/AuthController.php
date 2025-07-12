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
            'message' => 'Doğrulama kodu e-postanıza gönderildi',
            'requires_verification' => true,
        ], 200);
    }

    public function login(Request $request){

        $credentials = $request->validate([
            'loginUsername' => 'required',
            'loginPassword' => 'required',
        ], [
                'loginUsername.required' => 'Kullanıcı adı zorunludur.',
                'loginPassword.required' => 'Şifre zorunludur.',
            ]);

        $user = User::where('username', $credentials['loginUsername'])->first();

        if (!$user || !Hash::check($credentials['loginPassword'], $user->password)) {
            return response()->json(['message' => 'Bilgiler yanlış'], 401);
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
            'password' => 'nullable|string|min:6',
            'description' => 'nullable|string|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
        ], [
            'name.required' => 'İsim alanı zorunludur.',
            'name.string' => 'İsim metin olmalıdır.',
            'name.max' => 'İsim en fazla 20 karakter olabilir.',

            'email.required' => 'Email alanı zorunludur.',
            'email.string' => 'Email metin olmalıdır.',
            'email.max' => 'Email en fazla 50 karakter olabilir.',
            'email.unique' => 'Bu email zaten kullanılıyor.',

            'username.required' => 'Kullanıcı adı zorunludur.',
            'username.string' => 'Kullanıcı adı metin olmalıdır.',
            'username.max' => 'Kullanıcı adı en fazla 20 karakter olabilir.',
            'username.unique' => 'Bu kullanıcı adı zaten alınmış.',

            'password.string' => 'Şifre metin olmalıdır.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',

            'image.image' => 'Dosya bir resim olmalıdır.',
            'image.mimes' => 'Profil resmi formatı jpeg, png, jpg, gif veya svg olmalıdır.',
            'image.max' => 'Profil resmi en fazla 10MB olabilir.',

            'coverImage.image' => 'Kapak resmi bir resim olmalıdır.',
            'coverImage.mimes' => 'Kapak resmi formatı jpeg, png, jpg, gif veya svg olmalıdır.',
            'coverImage.max' => 'Kapak resmi dosyası en fazla 10MB olabilir.',

            'description.string' => 'Açıklama metin biçiminde olmalıdır.',
            'description.max' => 'Açıklama en fazla 200 karakter olabilir.',
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

        if ($request->filled('description')) {
            $user->description = $request->description;
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
            'description' => $user->description ?? null,
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
                'description' => $user->description,
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
                    'description' => $user->description,
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
            $user = $request->user();

            // Kullanıcının FCM tokenını da sıfırla
            $user->fcm_token = null;
            $user->save();

            // Sanctum tokenları sil
            $user->tokens()->delete();

    return response()->json(['message' => 'Logged out successfully']);
    }

    public function verifyRegistration(Request $request){
        $request->validate([
            'code' => 'required|digits:6',
            'email' => 'required|email',
        ], [
            'code.required' => 'Doğrulama kodu boş olamaz.',
            'code.digits' => 'Doğrulama kodunun 6 haneli olması gerekmektedir.',

        ]);

        $cacheKey = 'reg_verification:'.$request->email;
        $pendingRegistration = Cache::get($cacheKey);

        // Add proper null checks
        if (!$pendingRegistration || !isset($pendingRegistration['verification_code'])) {
            return response()->json([
                'message' => 'Doğrulama oturumu sona erdi veya geçersiz. Lütfen tekrar kayıt olun.'
            ], 422);
        }

        if ($request->code !== $pendingRegistration['verification_code']) {
            return response()->json([
                'message' => 'Geçersiz doğrulama kodu'
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

    public function resendVerification(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        $cacheKey = 'reg_verification:'.$request->email;
        $pendingRegistration = Cache::get($cacheKey);

        // Check if pending registration exists
        if (!$pendingRegistration) {
            return response()->json([
                'message' => 'Bekleyen kayıt bulunamadı. Lütfen tekrar kayıt olun.',
            ], 422);
        }

        // Generate a new 6-digit verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update the verification code in the existing data
        $pendingRegistration['verification_code'] = $verificationCode;
        $pendingRegistration['created_at'] = now();

        // Store updated data back in cache
        Cache::put($cacheKey, $pendingRegistration, now()->addMinutes(15));

        // Send verification email
        Mail::to($request->email)->send(new VerificationEmail($verificationCode));

        return response()->json([
            'message' => 'Doğrulama kodu e-postanıza tekrar gönderildi.',
        ], 200);
    }

    public function requestPasswordReset(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'E-posta alanı boş bırakılamaz.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists' => 'Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate a 6-digit reset code
        $resetCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store reset data in cache (expires in 15 minutes)
        $cacheKey = 'password_reset:'.$request->email;
        Cache::put($cacheKey, [
            'email' => $request->email,
            'reset_code' => $resetCode,
            'created_at' => now(),
        ], now()->addMinutes(15));

        // Send reset code via email
        Mail::to($request->email)->send(new VerificationEmail($resetCode));

        return response()->json([
            'message' => 'Şifre sıfırlama kodu e-postanıza gönderildi.',
        ], 200);
    }


    public function verifyResetCode(Request $request){

            $request->validate([
                'email' => 'required|email|exists:users,email',
                'code' => 'required|digits:6',
            ], [
                'email.required' => 'E-posta alanı boş bırakılamaz.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'email.exists' => 'Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.',

                'code.required' => 'Doğrulama kodu alanı zorunludur.',
                'code.digits' => 'Doğrulama kodu 6 haneli olmalıdır.',
            ]);

            $cacheKey = 'password_reset:'.$request->email;
            $resetData = Cache::get($cacheKey);

            // Check if reset data exists
            if (!$resetData || !isset($resetData['reset_code'])) {
                return response()->json([
                    'message' => 'Oturum süresi doldu veya geçersiz. Lütfen yeni bir kod isteyin.',
                ], 422);
            }

            // Verify the code
            if ($request->code !== $resetData['reset_code']) {
                return response()->json([
                    'message' => 'Geçersiz sıfırlama kodu.',
                ], 422);
            }

            // Generate a temporary token for password reset
            $resetToken = Str::random(60);
            Cache::put('password_reset_token:'.$request->email, [
                'email' => $request->email,
                'token' => $resetToken,
                'created_at' => now(),
            ], now()->addMinutes(15));

            // Clear the reset code from cache
            Cache::forget($cacheKey);

            return response()->json([
                'message' => 'Kod başarıyla doğrulandı.',
                'reset_token' => $resetToken,
            ], 200);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'newPassword' => 'required|min:6|confirmed',
            'reset_token' => 'required|string',
        ], [
            'email.required' => 'E-posta alanı boş bırakılamaz.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists' => 'Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.',

            'newPassword.required' => 'Yeni şifre alanı zorunludur.',
            'newPassword.min' => 'Yeni şifre en az 6 karakter olmalıdır.',
            'newPassword.confirmed' => 'Yeni şifre ile şifre tekrarı uyuşmuyor.',

            'reset_token.required' => 'Şifre sıfırlama anahtarı gereklidir.',
            'reset_token.string' => 'Şifre sıfırlama anahtarı geçerli bir metin olmalıdır.',
        ]);

        $cacheKey = 'password_reset_token:'.$request->email;
        $resetData = Cache::get($cacheKey);

        // Check if reset token exists and is valid
        if (!$resetData || $request->reset_token !== $resetData['token']) {
            return response()->json([
                'message' => 'Geçersiz veya süresi dolmuş sıfırlama belirteci.',
            ], 422);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->newPassword),
        ]);

        // Clear the reset token from cache
        Cache::forget($cacheKey);

        return response()->json([
            'message' => 'Şifre başarıyla sıfırlandı.',
        ], 200);
    }

    public function resendResetCode(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'E-posta alanı boş bırakılamaz.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists' => 'Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.',
        ]);

        $cacheKey = 'password_reset:'.$request->email;
        $resetData = Cache::get($cacheKey);

        // Check if there is a pending reset request
        if (!$resetData) {
            return response()->json([
                'message' => 'Bekleyen sıfırlama isteği bulunamadı. Lütfen yeni bir kod isteyin.',
                'errors' => [
                    'email' => ['Bekleyen sıfırlama isteği bulunamadı.'],
                ],
            ], 422);
        }

        // Generate a new 6-digit reset code
        $resetCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update the existing cache entry
        $resetData['reset_code'] = $resetCode;
        $resetData['created_at'] = now();

        // Store updated data in cache (15-minute expiration)
        Cache::put($cacheKey, $resetData, now()->addMinutes(15));

        // Send the new reset code via email
        Mail::to($request->email)->send(new VerificationEmail($resetCode));

        return response()->json([
            'message' => 'Şifre sıfırlama kodu e-postanıza tekrar gönderilecektir.',
        ], 200);
    }

    public function updateFcmToken(Request $request){

        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'FCM token updated']);
    }


}
