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
        $facebook_users = FacebookUser::with(['conversations.messages' => function ($query) {
            $query->latest('created_at');
        }])->get()->sortByDesc(function ($user) {
            return optional($user->conversations->first())->messages->first()->created_at ?? null;
        });
        
        $perPage = 20;
        $currentPage = request()->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $facebook_users->slice($offset, $perPage)->values();
        $total = $facebook_users->count();
        
        $facebook_users = new \Illuminate\Pagination\LengthAwarePaginator($items, $total, $perPage, $currentPage);
        $facebook_users->setPath(request()->url());
        
        $facebook_users->appends(request()->query())->links('pagination::bootstrap-4');
        
        $facebook_users->onEachSide(2);
        
        return view('pages.conversations')->with('facebook_users' , $facebook_users);
    }
    /**
     * Display a listing of the resource.
     */
    public function conversation(FacebookConversation $conversation)
    {
        
        return view('pages.conversation')->with('conversation' , $conversation);
    }
}
