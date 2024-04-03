<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookUser extends Model
{
    use HasFactory;
    protected $fillable = ['facebook_user_id', 'name', 'email'];

    public function Conversation()
    {
        return FacebookConversation::where('user', $this->facebook_user_id)->first();
    }

    public static function Get_Conversations($page = null)
    {
        $facebook_users = self::select('facebook_users.facebook_user_id')
            ->addSelect(DB::raw('MAX(facebook_messages.created_at) AS max_created_at'))
            ->join('facebook_conversations', 'facebook_conversations.user', '=', 'facebook_users.facebook_user_id')
            ->leftJoin('facebook_messages', function ($join) {
                $join->on('facebook_messages.conversation', '=', 'facebook_conversations.facebook_conversation_id');
            })
            ->groupBy('facebook_users.facebook_user_id')
            ->orderByDesc('max_created_at')
            ->get();
        $conversations = [];
        foreach($facebook_users as $user){
            $conversations[] = self::where('facebook_user_id', $user->facebook_user_id)->first();
        }
        return $conversations;
    }
}
