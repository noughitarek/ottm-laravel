<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\RemarketingIntervalMessages;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RemarketingInterval extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'facebook_page_id', 'start_after', 'category', 'send_after_each', 'devide_by', 'template', 'template', 'is_active', 'deleted_at'];

    public function Pages()
    {
        $pages = FacebookPage::where('facebook_page_id', $this->facebook_page_id)->where('expired_at', null)->get();
        return $pages;
    }
    public function Template()
    {
        return MessagesTemplates::find($this->template);
    }
    public function Total()
    {
        return RemarketingIntervalMessages::where('remarketing', $this->id)->count();
    }
    public function Get_Supported_Conversations()
    {
        $now = Carbon::now();
        $start_after = Carbon::createFromTimestamp($now->timestamp - $this->start_after);
        $sendOn = Carbon::createFromTimestamp($now->timestamp - $this->send_after_each);
        $conversations = FacebookConversation::where('page', $this->facebook_page_id)
        ->where('started_at', '<', $start_after->toDateTimeString())
        ->where(function ($query) use ($sendOn) {
            $query->whereNotExists(function ($subquery )use ($sendOn) {
                $subquery->select(DB::raw(1))
                    ->from('remarketing_interval_messages')
                    ->whereColumn('facebook_conversation_id', 'facebook_conversations.facebook_conversation_id')
                    ->where('remarketing', $this->id)
                    ->where('deleted_at', null)
                    ->where('last_use', '>=',  $sendOn->toDateTimeString());
            });
        })
        ->orderBy('ended_at', 'desc');
    return array(
        $conversations->paginate(20)->oneachside(2),
        $conversations->count(),
        $start_after->toDateTimeString(),
        $sendOn->toDateTimeString()
    );
        
    }
    public function History()
    {
        return RemarketingIntervalMessages::where('remarketing', $this->id)->paginate(20)->onEachSide(2);
    }
    public function ResponseRate()
    {
        return [0, 0];
        $id = $this->id;
        $total = count(DB::select("select facebook_conversation_id from remarketing_messages where remarketing = $id group by facebook_conversation_id"));

        $conversations = count(DB::select("SELECT FM.conversation
        FROM remarketing_messages RM, facebook_messages FM, remarketings RS
        WHERE RS.id = $id
        AND RM.remarketing = $id
        AND FM.sented_from = 'user'
        AND RM.facebook_conversation_id = FM.conversation
        AND RM.last_use < FM.created_at
        AND RM.last_use = (
            SELECT MAX(last_use)
            FROM remarketing_messages
            WHERE remarketing = $id
            AND RM.facebook_conversation_id = facebook_conversation_id
        )
        GROUP BY FM.conversation
        "));
        return [(int)(($total != 0) ? ($conversations / $total)*100 : 0), $conversations];
    }
    public function OrderRate()
    {
        return [0, 0];
        $id = $this->id;
        $total = count(DB::select("select facebook_conversation_id from remarketing_messages where remarketing = $id group by facebook_conversation_id"));
        $orders = count(DB::select("SELECT OD.conversation
        FROM remarketing_messages RM, orders OD, remarketings RS
        WHERE RS.id = $id
        AND RM.remarketing = $id
        AND RM.facebook_conversation_id = OD.conversation
        AND RM.last_use < OD.created_at
        AND RM.last_use = (
            SELECT MAX(last_use)
            FROM remarketing_messages
            WHERE remarketing = $id
            AND RM.facebook_conversation_id = facebook_conversation_id
        )
        GROUP BY OD.conversation;
        "));
        return [(int)(($total != 0) ? ($orders / $total)*100 : 0), $orders];
    }
}
