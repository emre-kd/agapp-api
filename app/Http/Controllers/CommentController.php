<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorepostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comment;
use App\Services\FirebaseNotificationService;


class CommentController extends Controller
{


    // Get paginated comments for a post
    public function index(Request $request, $postId) {
        $perPage = $request->query('per_page', 10); // Default to 10 comments per page
        $comments = Comment::where('post_id', $postId)
            ->with(['post.user', 'user'])
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

            $comment->load([
                'user',
                'post' => function ($query) {
                    $query->withCount('comments')->with('user');
                },
            ]);

           $data = [
            'post_id' => (string) $comment->post_id,
            'id' => (string) $comment->id,
            'user_id' => (string) $comment->user_id,

            // 🔥 Nested array yerine JSON string gönderiyoruz
            'post' => json_encode([
                'id' => $comment->post->id,
                'text' => $comment->post->text,
                'media' => $comment->post->media,
                'created_at' => $comment->post->created_at->toISOString(),
                'comments_count' => $comment->post->comments_count ?? 0,
                'likes_count' => $comment->post->likes_count ?? 0,
                'user' => [
                    'id' => $comment->post->user->id,
                    'name' => $comment->post->user->name,
                    'username' => $comment->post->user->username,
                    'image' => $comment->post->user->image,
                ],
            ])
        ];

            // 🔔 Bildirim gönder
            $postOwner = $comment->post->user;
            if ($postOwner->id !== Auth::id() && $postOwner->fcm_token) {
                $fcm = new FirebaseNotificationService();
                $fcm->sendToToken(
                    $postOwner->fcm_token,
                    'Yeni Yorum!',
                    "{$comment->user->name} gönderinize yorum yaptı: {$comment->comment}",
                        $data

                );
            }

            return response()->json([
                'success' => true,
                'data' => $comment,
                'notify' => true,
                'message' => 'Comment added successfully',
            ], 201);
        }



    public function getUserComments(){

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $comments = $user->comments()
            ->with(['post.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform each comment item
        $transformedComments = $comments->getCollection()->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'post_id' => $comment->post_id,
                'created_at' => $comment->created_at->toDateTimeString(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'username' => '@' . $comment->user->username,
                    'image' => $comment->user->image,
                ],
                'post' => [
                    'id' => $comment->post->id,
                    'text' => $comment->post->text,
                    'created_at' => $comment->post->created_at->toDateTimeString(),
                    'media' => $comment->post->media,
                    'comments_count' => $comment->post->comments->count(),
                    'user' => [
                        'id' => $comment->post->user->id,
                        'username' => '@' . $comment->post->user->username,
                        'name' => $comment->post->user->name,
                        'image' => $comment->post->user->image,
                    ],
                ],
            ];
        });


        $comments->setCollection($transformedComments);

        return response()->json(['comments' => $comments], 200);
    }

    public function getSearchedUserComments(Request $request, $userId){

        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Find the user by userId
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Fetch comments for the specified user
        $comments = $user->comments()
            ->with(['post.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Transform each comment item
        $transformedComments = $comments->getCollection()->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'post_id' => $comment->post_id,
                'created_at' => $comment->created_at->toDateTimeString(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'username' => '@' . $comment->user->username,
                    'image' => $comment->user->image,
                ],
                'post' => [
                    'id' => $comment->post->id,
                    'text' => $comment->post->text,
                    'created_at' => $comment->post->created_at->toDateTimeString(),
                    'media' => $comment->post->media,
                    'comments_count' => $comment->post->comments->count(),
                    'user' => [
                        'id' => $comment->post->user->id,
                        'username' => '@' . $comment->post->user->username,
                        'name' => $comment->post->user->name,
                        'image' => $comment->post->user->image,
                    ],
                ],
            ];
        });

        // Replace the original collection with the transformed one
        $comments->setCollection($transformedComments);

        return response()->json(['comments' => $comments], 200);
    }



    /*
    public function destroy($id)
    {
        $comment = Comment::where('user_id', Auth::id())->findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
    */
}
