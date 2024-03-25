<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\FacebookUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationsController extends Controller
{
    public function index()
    {
        $accessToken = config('settings.access_token');
        $userId = $accessToken->Page()->Get_User()['id'];

        $users = FacebookUser::where("facebook_user_id", "!=", $userId)
        ->orderByDesc(DB::raw('(
            SELECT MAX(created_at) FROM messages
            WHERE sented_by = facebook_user_id OR sented_to = facebook_user_id
        )'))
        ->paginate(10)->onEachSide(2);


    
        return view('pages.conversations.index')->with('users', $users);
    }
    public function conversation($conversation)
    {
        $accessToken = config('settings.access_token');
        $userId = $accessToken->Page()->Get_User()['id'];

        $users = FacebookUser::where("facebook_user_id", "!=", $userId)
        ->orderByDesc(DB::raw('(
            SELECT MAX(created_at) FROM messages
            WHERE sented_by = facebook_user_id OR sented_to = facebook_user_id
        )'))
        ->paginate(10)->onEachSide(2);
        $user = FacebookUser::where('facebook_user_id', $conversation)->first();
        return view('pages.conversations.conversation')->with('users', $users)->with('suser', $user);
    }
}
