<?php

namespace App\Models;

use App\Models\ResponderTemplate;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Responder extends Model
{
    use HasFactory;
    protected $fillable = ['page', 'name', 'deleted_at', 'is_active'];
    public function Pages()
    {
        $pages = FacebookPage::where('facebook_page_id', $this->page)->where('expired_at', null)->get();
        return $pages;
    }
    public function Template()
    {

        return ResponderTemplate::where('responder', $this->id)->get();
    }
    public function Total()
    {
        return ResponderMessage::where('responder', $this->id)->count();
    }
    public function ResponseRate()
    {
        return [0,0];
    }
    public function OrderRate()
    {
        return [0,0];
    }
    public function History()
    {
        return ResponderMessage::where('responder', $this->id)->paginate(20)->onEachSide(2);
    }
    public function Get_Supported_Conversations()
    {
        /*$query = FacebookConversation::where('page', $this->page)
        ->leftJoin('facebook_messages as FM', function($join) {
            $join->on('FM.conversation', '=', DB::raw('facebook_conversation_id'))
            ->where('FM.sented_from', '=', 'page')
            ->where(function ($query) {
                $query->whereRaw('FM.created_at = (
                        SELECT MIN(created_at) FROM facebook_messages WHERE conversation = FM.conversation
                    )')
                    ->havingRaw('COUNT(FM.sented_by) <> 1');
            });
        })
        ->whereNull('FM.conversation')
        ->groupBy('facebook_conversations.id')
        ->select('facebook_conversation_id', DB::raw('COUNT(FM.id) AS total'), 'facebook_conversations.*');*/
        $conversations = DB::select("SELECT *
        FROM `facebook_messages` FM
        JOIN `facebook_conversations` FC ON FC.facebook_conversation_id = FM.conversation
        WHERE FC.page = $this->page
        AND FM.sented_from = 'page'
        AND (
            (SELECT COUNT(*) FROM `facebook_messages` WHERE conversation = FM.conversation) = 1
            OR
            FM.created_at = (SELECT MIN(created_at) FROM `facebook_messages` WHERE conversation = FM.conversation)
        );
        ");
        return $conversations;
    }
}
