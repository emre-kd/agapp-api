<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorepostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comment;

class CommentController extends Controller
{
    // Get paginated comments for a post
    public function index(Request $request, $postId)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 comments per page
        $comments = Comment::where('post_id', $postId)
            ->with('user') // Eager load user data
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $comments->items(),

            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'total' => $comments->total(),
                'per_page' => $comments->perPage(),

            ],
        ]);
    }

    // Store a new comment
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'comment' => $request->comment,
        ]);

        // Load user data for the response
        $comment->load('user');

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment added successfully',
        ], 201);
    }

    // Delete a comment (optional, if needed)
    public function destroy($id)
    {
        $comment = Comment::where('user_id', Auth::id())->findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
