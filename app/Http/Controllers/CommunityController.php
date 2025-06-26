<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorepostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Community;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }


        $community = Community::withCount('users')
            ->findOrFail($user->community_id);

        return response()->json([
            'community' => [
                'id' => $community->id,
                'code' => $community->code,
                'name' => $community->name,
                'users_count' => $community->users_count ?? 0,
                'created_at' => $community->created_at->locale('tr')->diffForHumans() . " / " . $community->created_at->format('d.m.Y'),
                'user' => [
                    'username' => '@' . $community->user->username,
                    'id' => $community->user->id,
                    'name' => $community->user->name,
                    'image' => $community->user->image,
                ],
            ]
        ], 200);
    }


}
