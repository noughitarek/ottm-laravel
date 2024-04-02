<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Remarketing;
use Illuminate\Console\Command;
use App\Models\RemarketingMessages;
use App\Models\FacebookConversation;

class RemarketingSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remarketing-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(!config('settings.scheduler.remarketing_send'))
            exit;
        $now = Carbon::now();
        $remarketings = Remarketing::where('deleted_at', null)->get();
        
        foreach ($remarketings as $remarketing) {
            $conversations = FacebookConversation::where('page', $remarketing->facebook_page_id)->get();
            
            foreach ($conversations as $conversation) {
                if($remarketing->last_message_from != "any" && $conversation->Messages()->first()->sented_from != $remarketing->last_message_from)continue;
                $order = Order::where('conversation', $conversation->facebook_conversation_id)->first();
                if($remarketing->make_order && !$order)
                    continue;
                elseif(!$remarketing->make_order && $order)
                    continue;
                $last_use = RemarketingMessages::where('remarketing', $remarketing->id)->where('facebook_conversation_id', $conversation->facebook_conversation_id)->first();
                if($last_use)continue;
                
                if($remarketing->since == 'conversation_start'){
                    $created_at = $conversation->Messages()->last();
                }elseif($remarketing->since == 'conversation_end'){
                    $created_at = $conversation->Messages()->first();
                }elseif($remarketing->since =='last_from_user'){
                    $created_at = $conversation->Messages()->where('sented_from', 'user')->first();
                }elseif($remarketing->since =='last_from_page'){
                    $created_at = $conversation->Messages()->where('sented_from', 'page')->first();
                }

                if(!$created_at)continue;
                $messageCreatedAt = Carbon::parse($created_at->created_at);
                $sendAfterTime = (int)$remarketing->send_after+(int)$messageCreatedAt->timestamp;
                $sendAfterTime = Carbon::createFromTimestamp($sendAfterTime);
                if ($sendAfterTime->lessThanOrEqualTo($now))
                {
                    RemarketingMessages::create([
                        'remarketing' => $remarketing->id,
                        'facebook_conversation_id' => $conversation->facebook_conversation_id,
                        'last_use' => now(),
                    ]);
                    $conversation->Remarketing($remarketing);
                }
            }
        }
            
    }
}
