<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use App\Models\FacebookConversation;

class AffectConversationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:affect-conversations-command';

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
        $orders = Order::whereNull('conversation')
            ->where('validated_at', '>=', now()->subDays(7))
            ->get();
        
        foreach($orders as $order){
            
            $conversation = null;
            if($order->intern_tracking != null)
            {
                $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($order) {
                    $query->select('conversation')
                    ->from('facebook_messages')
                    ->where('message', 'like', '%'.$order->intern_tracking.'%')
                    ->groupBy('conversation')
                    ->get();
                })->first();
                if($conversation){
                    $conversation = $conversation->facebook_conversation_id;
                }
            }
            if(!$conversation)
            {
                $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($order) {
                    $query->select('conversation')
                    ->from('facebook_messages')
                    ->where('message', 'like', '%'.$order->phone.'%')
                    ->groupBy('conversation')
                    ->get();
                })->first();
                if($conversation){
                    $conversation = $conversation->facebook_conversation_id;
                }
            }
            $order->conversation = $conversation;
            $order->save();
        }
        exit;
    }
}
