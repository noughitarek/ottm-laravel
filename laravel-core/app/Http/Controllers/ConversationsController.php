<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
use App\Models\FacebookUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;
use Illuminate\Pagination\LengthAwarePaginator;

class ConversationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facebook_pages = FacebookPage::where('type', 'business')->where('expired_at', null)->paginate(20)->onEachSide(2);
        return view('pages.conversations.pages')->with('facebook_pages' , $facebook_pages);
    }

    /**
     * Display a listing of the resource.
     */
    public function conversations($facebook_page)
    {
        $facebook_page = FacebookPage::where('facebook_page_id', $facebook_page)->first();
        if(!$facebook_page){
            return abort(404);
        }
        /*$facebook_users = FacebookUser::orderByDesc(
            DB::raw('(
                SELECT MAX(created_at) FROM facebook_messages
                WHERE conversation = (
                    SELECT facebook_conversation_id FROM facebook_conversations
                    WHERE facebook_conversations.page = "'.$facebook_page->facebook_page_id.'"
                    AND facebook_conversations.user = facebook_users.facebook_user_id 
                    ORDER BY created_at DESC
                    LIMIT 1
                )
            )')
        )->paginate(20)->onEachSide(2);*/
        
        $facebook_users = FacebookConversation::where('page', $facebook_page->facebook_page_id)->get();
        $facebook_users->transform(function ($item) {
            $item->first_message_created_at = $item->Messages()->first()->created_at;
            return $item;
        });
        $facebook_users = $facebook_users->sortByDesc('first_message_created_at');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $facebook_users->slice(($currentPage - 1) * 20, 20)->all();
        $facebook_users = new LengthAwarePaginator($items, $facebook_users->count(), 20, $currentPage);
        return view('pages.conversations.conversations')->with('facebook_users' , $facebook_users)->with('facebook_page', $facebook_page);
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
        return view('pages.conversations.conversation')->with('conversation' , $conversation);
    }

    public function sendmessage(Request $request, $conversation)
    {
        $conversation = FacebookConversation::where('facebook_conversation_id', $conversation)->first();
        $conversation->Send_Message($request->message);
        return back()->with("success", "Message has been sented successfully");
    }
}
