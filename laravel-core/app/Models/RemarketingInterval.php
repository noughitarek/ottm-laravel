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
        return RemarketingIntervalTemplates::where('remarketing', $this->id)->orderBy('order', 'ASC')->get();
    }
    public function Get_Template()
    {
        return RemarketingIntervalTemplates::where('remarketing', $this->id)->where('used', false)->orderBy('order', 'ASC')->first();
    }
    public function Total()
    {
        return RemarketingIntervalMessages::where('remarketing', $this->id)->count();
    }
    public function Get_Supported_Conversations0()
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
    public function All_Supported_Conversations()
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
        echo $conversations->count()."\n";
        return $conversations->count();
    }
    public function Check_Interval()
    {
        $now = Carbon::now();
        $message = RemarketingIntervalMessages::where('remarketing', $this->id)
            ->where('deleted_at', null)
            ->orderBy('last_use', 'desc')
            ->first();
        if ($message) {
            $sendOn = Carbon::parse($message->last_use)->addSeconds($this->send_after_each);
            if ($now <= $sendOn) {
                return true;
            }
        }
        return false;
    }
    public function Get_Supported_Conversations($iter=0)
    {
        if(!$this->Get_Template())
        {
            RemarketingIntervalTemplates::where('remarketing', $this->id)->update(['used' => false]);
        }
        $now = Carbon::now();
        $start_after = Carbon::createFromTimestamp($now->timestamp - $this->start_after);
        $sendOn = Carbon::createFromTimestamp($now->timestamp - $this->send_after_each);
        if($this->Check_Interval())
        {
            return array(
                FacebookConversation::limit(0),
                0,
                $start_after->toDateTimeString(),
                $sendOn->toDateTimeString()
            );
        }

        $conversationsQuery = FacebookConversation::where('page', $this->facebook_page_id)
        ->where('started_at', '<', $start_after->toDateTimeString())
        ->where(function ($query) use ($sendOn) {
            $query->whereNotExists(function ($subquery)use ($sendOn) {
                $subquery->select(DB::raw(1))
                    ->from('remarketing_interval_messages')
                    ->whereColumn('facebook_conversation_id', 'facebook_conversations.facebook_conversation_id')
                    ->where('remarketing', $this->id)
                    ->where('deleted_at', null)
                    ->where('last_use', '>=',  $sendOn->toDateTimeString());
            });
        })
        ->whereNotIn('facebook_conversation_id', function ($query) {
            $query->select('facebook_conversation_id')
                ->from('remarketing_interval_messages')
                ->where('remarketing', $this->id)
                ->where('deleted_at', null);
        })
        ->orderBy('ended_at', 'desc');
        $conversations = $conversationsQuery->limit((int)($conversationsQuery->count() / $this->devide_by)+1)->get();
        if($conversations->count() == 0 && $iter<10)
        {
            $this->Get_Template()->update(['used' => true]);
            RemarketingIntervalMessages::where('remarketing', $this->id)->update(['deleted_at'=> now()]);
            $this->Get_Supported_Conversations($iter+1);
        }

        return array(
            $conversations,
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
