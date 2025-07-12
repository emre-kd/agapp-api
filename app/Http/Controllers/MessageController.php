<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorepostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Message;
use App\Services\FirebaseNotificationService;


class MessageController extends Controller
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



        $query = Message::query()
            ->where(function ($q) use ($user, $request) {
                // Messages where user is sender or receiver
                if ($request->receiver_id) {
                    $q->where(function ($subQ) use ($user, $request) {
                        $subQ->where('sender_id', $user->id)
                            ->where('receiver_id', $request->receiver_id);
                    })->orWhere(function ($subQ) use ($user, $request) {
                        $subQ->where('sender_id', $request->receiver_id)
                            ->where('receiver_id', $user->id);
                    });
                }
            })
            ->when($request->community_id, function ($q) use ($request) {
                // Filter by community if provided
                $q->where('community_id', $request->community_id);
            })
            ->orderBy('created_at', 'desc')
            ->with(['sender', 'receiver']); // Eager load sender/receiver data

        $messages = $query->paginate(20); // Paginate results

        return response()->json([
            'status' => 'success',
            'data' => $messages,
        ], 200);
    }


     public function getConversations(Request $request)
    {
          $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $userId = $user->id;
    $communityId = $user->community_id;

    $conversations = DB::table('messages')
        ->select(DB::raw('
            LEAST(sender_id, receiver_id) AS user_one,
            GREATEST(sender_id, receiver_id) AS user_two,
            MAX(id) as last_message_id
        '))
        ->where('community_id', $communityId)
        ->where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->groupBy('user_one', 'user_two')
        ->pluck('last_message_id');

    $messages = \App\Models\Message::with('sender', 'receiver')
        ->whereIn('id', $conversations)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($message) use ($userId) {
            // Konuşulan kişi kim?
            $otherUser = $message->sender_id == $userId ? $message->receiver : $message->sender;

            return [
                'user_id' => $otherUser->id,
                'name' => $otherUser->name,
                'username' => '@' . $otherUser->username,
                'image' => $otherUser->image,
                'last_message' => $message->text,
                'created_at' => $message->created_at->diffForHumans(),
            ];
        });

    return response()->json([
        'conversations' => $messages,
        'status' => 'success',
        'count' => $messages->count(),
    ]);

    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:250',
            'receiver_id' => 'required|exists:users,id', // Validate receiver exists
            'community_id' => 'nullable|exists:communities,id', // Optional community
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create([
        'text' => $request->text,
        'sender_id' => $user->id,
        'receiver_id' => $request->receiver_id,
        'community_id' => $request->community_id ?? $user->community_id,
        ]);

        // 🔔 Bildirim gönder (FCM)
        $receiver = User::find($request->receiver_id);
        if ($receiver && $receiver->fcm_token) {
            $fcm = new FirebaseNotificationService();
            $fcm->sendToToken(
                $receiver->fcm_token,
                'Yeni Mesaj!',
                "{$user->name} size bir mesaj gönderdi",
                [
                    'type' => 'chat',
                    'sender_id' => (string) $user->id,
                    'sender_name' => $user->name,
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $message->id,
                'text' => $message->text,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'community_id' => $message->community_id,
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at,
            ],
        ], 201);
    }



}
