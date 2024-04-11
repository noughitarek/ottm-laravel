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
        $conversations = FacebookUser::Get_Conversations($facebook_page->facebook_page_id);
        return view('pages.conversations.conversations')->with('conversations' , $conversations)->with('facebook_page', $facebook_page);
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
