<?php

namespace App\Http\Controllers;

use App\Models\FacebookUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;

class ConversationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facebook_users = FacebookUser::orderByDesc(
            DB::raw('(
                SELECT MAX(created_at) FROM facebook_messages
                WHERE conversation = (
                    SELECT facebook_conversation_id FROM facebook_conversations
                    WHERE facebook_conversations.user = facebook_users.facebook_user_id
                    ORDER BY created_at DESC
                    LIMIT 1
                )
            )')
        )->paginate(20)->onEachSide(2);
        return view('pages.conversations')->with('facebook_users' , $facebook_users);
    }
    /**
     * Display a listing of the resource.
     */
    public function conversation($conversation)
    {
        $conversation = FacebookConversation::where('facebook_conversation_id', $conversation)->first();
        if(!$conversation){
            return abort(404);
        }
        return view('pages.conversation')->with('conversation' , $conversation);
    }
}
