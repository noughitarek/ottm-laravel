<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\MessagesTemplates;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remarketing extends Model
{
    use HasFactory;
    protected $fillable = ["name", "facebook_page_id", 'category', "send_after", "resend_after", "last_message_from", "make_order", "since", "photos", "video", "template", "message", "deleted_at", "expire_after", "is_active", "start_time", "end_time"];

    public function Pages()
    {
        $pages = FacebookPage::where('facebook_page_id', $this->facebook_page_id)->where('expired_at', null)->get();
        return $pages;
    }
    public function Send_After()
    {
        if ($this->send_after/60/60/24 > 1) {
            return (string)($this->send_after/60/60/24). ' days';
        } elseif ($this->send_after/60/60 > 1) {
            return (string)($this->send_after/60/60). ' hours';
        } elseif ($this->send_after/60 > 1) {
            return (string)($this->send_after/60). ' minutes';
        } else {
            return (string)($this->send_after). ' seconds';
        }
    }
    public function Expire_After()
    {
        if ($this->expire_after/60/60/24 > 1) {
            return (string)($this->expire_after/60/60/24). ' days';
        } elseif ($this->expire_after/60/60 > 1) {
            return (string)($this->expire_after/60/60). ' hours';
        } elseif ($this->expire_after/60 > 1) {
            return (string)($this->expire_after/60). ' minutes';
        } else {
            return (string)($this->expire_after). ' seconds';
        }
    }
    public function Get_Supported_Conversations0()
    {
        $now = Carbon::now();
        $supported = [];
        $conversations = FacebookConversation::where('page', $this->facebook_page_id)->get();
        foreach ($conversations as $conversation) {
            if(count($supported)==config('settings.limits.max_simultaneous_message'))break;
            if($this->last_message_from != "any" && $conversation->Messages()->first()->sented_from != $this->last_message_from)continue;
            $order = Order::where('conversation', $conversation->facebook_conversation_id)->first();
            if(!$this->make_order && $order)continue;
            if($this->resend_after == null)
            {
                $last_use = RemarketingMessages::where('remarketing', $this->id)->where('facebook_conversation_id', $conversation->facebook_conversation_id)->first();
                if($last_use)continue;
            }
            else
            {

                $last_use = RemarketingMessages::where('remarketing', $this->id)
                ->where('facebook_conversation_id', $conversation->facebook_conversation_id)
                ->where('expire_at', '>=', now())
                ->first();
            }
            
            if($this->since == 'conversation_start'){
                $created_at = $conversation->Messages()->last();
            }elseif($this->since == 'conversation_end'){
                $created_at = $conversation->Messages()->first();
            }elseif($this->since =='last_from_user'){
                $created_at = $conversation->Messages()->where('sented_from', 'user')->first();
            }elseif($this->since =='last_from_page'){
                $created_at = $conversation->Messages()->where('sented_from', 'page')->first();
            }
            
            if(!$created_at)continue;
            $messageCreatedAt = Carbon::parse($created_at->created_at);
            $sendAfterTime = (int)$this->send_after+(int)$messageCreatedAt->timestamp;
            $expireTime = (int)$this->expire_after+(int)$messageCreatedAt->timestamp+(int)$this->send_after;
            $sendAfterTime = Carbon::createFromTimestamp($sendAfterTime);
            $expireTime = Carbon::createFromTimestamp($expireTime);
            if($sendAfterTime->lessThanOrEqualTo($now))
            {
                if($this->expire_after == null)
                {
                    $supported[] = $conversation->User();
                }
                elseif($expireTime->greaterThanOrEqualTo($now))
                {
                    $supported[] = $conversation->User();
                }
            }
            
        }
        return collect($supported);
    }
    public function Template()
    {
        return MessagesTemplates::find($this->template);
    }
    public function Start_date()
    {
        $now = Carbon::now();
        return Carbon::createFromTimestamp($now->timestamp  - $this->send_after)->toDateTimeString();
    }
    public function End_date()
    {
        $now = Carbon::now();
        return Carbon::createFromTimestamp($now->timestamp  - $this->send_after + $this->expire_after)->toDateTimeString();
    }
    public function Get_Supported_Conversations()
    {
        $now = Carbon::now();

        $started_at = Carbon::createFromTimestamp($now->timestamp - $this->send_after);
        $ended_at = Carbon::createFromTimestamp($now->timestamp  - $this->send_after + $this->expire_after);
        
        /*if($this->resend_after == null)
        {
            $last_use = RemarketingMessages::where('remarketing', $this->id)->where('facebook_conversation_id', $conversation->facebook_conversation_id)->first();
            if($last_use)continue;
        }
        else
        {

            $last_use = RemarketingMessages::where('remarketing', $this->id)
            ->where('facebook_conversation_id', $conversation->facebook_conversation_id)
            ->where('expire_at', '>=', now())
            ->first();
        }*/
        if($this->resend_after == null)
        {
            $conversations = FacebookConversation::where('page', $this->facebook_page_id)
            ->where($this->since, '>=', $started_at->toDateTimeString())
            ->where($this->since, '<=', $ended_at->toDateTimeString())
            ->where(function ($query) {
                if ($this->last_message_from !== 'any') {
                    $query->where('last_from', $this->last_message_from);
                }
            })
            ->where(function ($query) {
                if ($this->make_order === 0) {
                    $query->where('make_order', $this->make_order);
                }
            })
            ->where(function ($query) {
                $query->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('remarketing_messages')
                        ->whereColumn('facebook_conversation_id', 'facebook_conversations.facebook_conversation_id')
                        ->where('remarketing', $this->id);
                });
            })
            ->orderBy('ended_at', 'desc');  
        }
        else 
        {
            $started_at = min(Carbon::createFromTimestamp($now->timestamp - $this->send_after),
            Carbon::createFromTimestamp($this->created_at->timestamp + $this->send_after));
            $conversations = FacebookConversation::where('page', $this->facebook_page_id)
            ->where($this->since, '>=', $started_at->toDateTimeString())
            #->where($this->since, '<=', $ended_at->toDateTimeString())
            ->where(function ($query) {
                if ($this->last_message_from !== 'any') {
                    $query->where('last_from', $this->last_message_from);
                }
            })
            ->where(function ($query) {
                if ($this->make_order === 0) {
                    $query->where('make_order', $this->make_order);
                }
            })
            ->where(function ($query) {
                $query->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('remarketing_messages')
                        ->whereColumn('facebook_conversation_id', 'facebook_conversations.facebook_conversation_id')
                        ->where('remarketing', $this->id)
                        ->where('expire_at', '>', now());
                });
            })
            ->orderBy('ended_at', 'desc');  
        }
        return array(
            $conversations->take(config('settings.limits.max_simultaneous_message'))->get(),
            $conversations->count(),
            $started_at->toDateTimeString(),
            $ended_at->toDateTimeString()
        );
        
    }
    public function History()
    {
        return RemarketingMessages::where('remarketing', $this->id)->paginate(20)->onEachSide(2);
    }
    public function Total()
    {
        return RemarketingMessages::where('remarketing', $this->id)->count();
    }
    public function ResponseRate()
    {
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
        $id = $this->id;
        $total = count(DB::select("select facebook_conversation_id from remarketing_messages where remarketing = $id group by facebook_conversation_id"));
        $orders = count(DB::select("SELECT FM.conversation
        FROM remarketing_messages RM, facebook_messages FM, remarketings RS
        WHERE FM.`message` LIKE  '%سجلت الطلبية تاعك خلي برك الهاتف مفتوح باه يعيطلك الليفرور و ما تنساش الطلبية على خاطر رانا نخلصو عليها جزاك الله%'
        AND RS.id = $id
        AND RM.remarketing = $id
        AND RM.facebook_conversation_id = FM.conversation
        AND RM.last_use < FM.created_at
        AND RM.last_use = (
            SELECT MAX(last_use)
            FROM remarketing_messages
            WHERE remarketing = $id
            AND RM.facebook_conversation_id = facebook_conversation_id
        )
        GROUP BY FM.conversation;
        "));
        return [(int)(($total != 0) ? ($orders / $total)*100 : 0), $orders];
    }
}
