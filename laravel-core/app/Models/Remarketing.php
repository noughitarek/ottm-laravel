<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\FacebookPage;
use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remarketing extends Model
{
    use HasFactory;
    protected $fillable = ["name", "facebook_page_id", "send_after", "last_message_from", "make_order", "since", "photos", "video", "message", "deleted_at", "expire_after", "is_active"];

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

    public function Get_Supported_Conversations()
    {
        $now = Carbon::now();
        $supported = [];
        $conversations = FacebookConversation::where('page', $this->facebook_page_id)->get();
            
        foreach ($conversations as $conversation) {
            if($this->last_message_from != "any" && $conversation->Messages()->first()->sented_from != $this->last_message_from)continue;
            $order = Order::where('conversation', $conversation->facebook_conversation_id)->first();
            if($this->make_order && !$order)
                continue;
            elseif(!$this->make_order && $order)
                continue;
            
            $last_use = RemarketingMessages::where('remarketing', $this->id)->where('facebook_conversation_id', $conversation->facebook_conversation_id)->first();
            if($last_use)continue;
            
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
            $expireTime = (int)$this->expire_after+(int)$messageCreatedAt->timestamp;
            $sendAfterTime = Carbon::createFromTimestamp($sendAfterTime);
            $expireTime = Carbon::createFromTimestamp($expireTime);
            if($sendAfterTime->lessThanOrEqualTo($now))
            {
                if($this->expire_after == null || $expireTime->greaterThanOrEqualTo($now))
                {
                    $supported[] = $conversation->User();
                }
            }
        }
        return collect($supported);
    }
}
