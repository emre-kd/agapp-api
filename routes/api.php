<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-registration', [AuthController::class, 'verifyRegistration']);
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

Route::post('/forgot-password', [AuthController::class, 'requestPasswordReset']);
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-reset-code', [AuthController::class, 'resendResetCode']);



Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {


    Route::put('/updateUser', [AuthController::class, 'updateUser']);
    Route::get('/getUser', [AuthController::class, 'getUser']);
    Route::get('/getUsers', [AuthController::class, 'getUsers']);
    Route::get('/getSearchedUser/{id}', [AuthController::class, 'getSearchedUser']);


    Route::get('/getSearchedUserPosts/{id}', [PostController::class, 'getSearchedUserPosts']);
    Route::post('/storePost', [PostController::class, 'store']);
    Route::get('/fetchPost', [PostController::class, 'index']);
    Route::get('/fetchUserPost', [PostController::class, 'indexUser']);
    Route::delete('/deleteUserPost/{id}', [PostController::class, 'destroy']);

    Route::get('/indexMessage', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/storeMessage', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/getConversations', [MessageController::class, 'getConversations']);


    Route::get('/getCommunities', [CommunityController::class, 'show']);


    Route::post('/posts/{postId}/like', [LikeController::class, 'toggle']);
    Route::get('/posts/{postId}/like-status', [LikeController::class, 'check']);

    Route::get('posts/{postId}/comments', [CommentController::class, 'index']);
    Route::post('comments', [CommentController::class, 'store']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);


    Route::get('/posts/most-liked', [PostController::class, 'mostLiked']);
    Route::get('/posts/most-commented', [PostController::class, 'mostCommented']);


    Route::get('/user/liked-posts', [PostController::class, 'getLikedPosts']);
    Route::get('/user/comments', [CommentController::class, 'getUserComments']);


    Route::get('/user/{userId}/liked-posts', [PostController::class, 'getSearchedUserLikedPosts']);
    Route::get('/user/{userId}/comments', [CommentController::class, 'getSearchedUserComments']);


    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);



    Route::post('/logout', [AuthController::class, 'logout']);
});
