<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
     public function toggle(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->first();

        if ($like) {
            // Unlike: Delete existing like
            $like->delete();
            $isLiked = false;
        } else {
            // Like: Create new like
            Like::create([
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);
            $isLiked = true;
        }

        return response()->json([
            'message' => $isLiked ? 'Post liked successfully' : 'Post unliked successfully',
            'like_count' => $post->likes()->count(),
            'is_liked' => $isLiked
        ], 200);
    }

   public function check(Request $request, $postId){
        if (!$postId) {
            return response()->json([
                'error' => 'Post ID is required'
            ], 400);
        }

        $post = Post::find($postId);
        if (!$post) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $isLiked = Auth::check() ? $post->isLikedBy(Auth::id()) : false;

        return response()->json([
            'like_count' => $post->likes()->count(),
            'is_liked' => $isLiked
        ], 200);
    }
}


