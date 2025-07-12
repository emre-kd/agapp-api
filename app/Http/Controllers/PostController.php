<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorepostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Services\FirebaseNotificationService;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get page and limit from query parameters (default to 1 and 10)
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);

        // Fetch paginated posts
        $posts = Post::with('user')
            ->where('community_id', $user->community_id)
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page)
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'created_at' => $post->created_at,
                    'comments_count' => $post->comments_count,
                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            });

        return response()->json(['posts' => $posts], 200);
    }

    public function mostLiked(Request $request){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get page and limit from query parameters (default to 1 and 5)
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);

        // Fetch paginated posts ordered by likes count
        $posts = Post::with('user')
            ->where('community_id', $user->community_id)
            ->withCount('likes') // Adds likes_count column
            ->withCount('comments')
            ->orderBy('likes_count', 'desc') // Order by number of likes
            ->orderBy('created_at', 'desc') // Secondary sort by creation date
            ->paginate($limit, ['*'], 'page', $page)
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'created_at' => $post->created_at,
                    'likes_count' => $post->likes_count, // Include likes count
                    'comments_count' => $post->comments_count, // Include comments count

                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            });

        return response()->json(['posts' => $posts], 200);
    }

    public function mostCommented(Request $request){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get page and limit from query parameters (default to 1 and 5)
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);

        // Fetch paginated posts ordered by comments count
        $posts = Post::with('user')
            ->where('community_id', $user->community_id)
            ->withCount('comments') // Adds comments_count column
            ->orderBy('comments_count', 'desc') // Order by number of comments
            ->orderBy('created_at', 'desc') // Secondary sort by creation date
            ->paginate($limit, ['*'], 'page', $page)
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'created_at' => $post->created_at,
                    'comments_count' => $post->comments_count, // Include comments count
                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            });

        return response()->json(['posts' => $posts], 200);
    }

    public function getLikedPosts() {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $likedPosts = $user->likedPosts()
            ->with('user') // Eager load the user who created the post
            ->orderBy('likes.created_at', 'desc')
            ->withCount('comments')
            ->paginate(5)
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'created_at' => $post->created_at,
                    'comments_count' => $post->comments_count,
                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            });

        return response()->json(['posts' => $likedPosts], 200);
    }


    public function getSearchedUserLikedPosts(Request $request, $userId){
        // Check if the authenticated user exists
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Find the user by userId
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Fetch liked posts for the specified user
        $likedPosts = $user->likedPosts()
            ->with('user') // Eager load the user who created the post
            ->orderBy('likes.created_at', 'desc')
            ->withCount('comments')
            ->paginate(5)
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'created_at' => $post->created_at,
                    'comments_count' => $post->comments_count,
                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            });

        return response()->json(['posts' => $likedPosts], 200);
    }


    public function getSearchedUserPosts(Request $request ,$userId) {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);

        $posts = Post::where('user_id', $userId)
        ->where('community_id', $user->community_id)
        ->orderBy('created_at', 'desc')
        ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'posts' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'comments_count' => $post->comments->count(),
                    'created_at' => $post->created_at,
                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            })
        ], 200);
    }


    public function indexUser(Request $request){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);

        $posts = Post::with('user')
            ->where('user_id', $user->id)
            ->where('community_id', $user->community_id)
            ->orderBy('created_at', 'desc')
            ->withCount('comments')
            ->paginate($limit, ['*'], 'page', $page)
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'text' => $post->text,
                    'media' => $post->media,
                    'created_at' => $post->created_at,
                    'comments_count' => $post->comments_count,
                    'user' => [
                        'username' => '@' . $post->user->username,
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'image' => $post->user->image,
                    ],
                ];
            });

        return response()->json(['posts' => $posts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

       $validator = Validator::make($request->all(), [
            'text' => 'required_without:media|nullable|string|max:250',
            'media' => 'required_without:text|nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov|max:10240',
        ], [
            // text alanı için
            'text.required_without' => 'Medya yoksa metin alanı zorunludur.',
            'text.string' => 'Metin sadece yazı içermelidir.',
            'text.max' => 'Metin en fazla 250 karakter olabilir.',

            // media alanı için
            'media.required_without' => 'Metin yoksa medya yüklemeniz gerekmektedir.',
            'media.file' => 'Yüklenen dosya geçerli bir dosya olmalıdır.',
            'media.mimes' => 'Yalnızca jpeg, png, jpg, gif, svg, mp4 ve mov formatları desteklenmektedir.',
            'media.max' => 'Medya en fazla 10 MB boyutunda olabilir.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = new Post;
        $post->text = $request->text;
        $post->user_id = $user->id;
        $post->community_id = $user->community_id;

        // Handle media upload (image/video)
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('posts/'), $fileName);
            $post->media = 'posts/' . $fileName; // Save the relative path in DB
        }

        $post->save();

       // 🔔 Bildirim gönder (FCM)
    $otherUsers = User::where('community_id', $user->community_id)
        ->where('id', '!=', $user->id)
        ->whereNotNull('fcm_token')
        ->get();

    foreach ($otherUsers as $receiver) {
        $fcm = new FirebaseNotificationService();
        $fcm->sendToToken(
            $receiver->fcm_token,
            'Yeni Gönderi!',
            "{$user->name} topluluğa yeni bir gönderi paylaştı.",
            [
                'type' => 'new_post',
                'post_id' => (string) $post->id,
                'sender_id' => (string) $user->id,
                'community_id' => (string) $user->community_id,
            ]
        );
    }

    return response()->json([
        'message' => 'Post sent successfully',
        'post' => [
            'id' => $post->id,
            'text' => $post->text,
            'media_url' => $post->media ? asset($post->media) : null,
            'user_id' => $post->user_id,
            'created_at' => $post->created_at,
        ],
    ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $post = Post::findOrFail($id);

        // Check if the user is authorized to delete the post
        if ($post->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the media file if it exists
        if ($post->media) {
            $mediaPath = public_path($post->media); // Get full path to the media file
            if (file_exists($mediaPath)) {
                unlink($mediaPath); // Delete the file from the server
            }
        }

        // Delete the post from the database
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
