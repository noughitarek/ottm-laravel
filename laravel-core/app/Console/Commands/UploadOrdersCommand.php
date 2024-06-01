<?php

namespace App\Console\Commands;

use App\Models\OrdersImport;
use Illuminate\Console\Command;
use App\Models\FacebookConversation;

class UploadOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-orders-command';

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
        $unUploadedOrders = OrdersImport::whereNull('uploaded_at')->orderBy('id', 'desc')->get();
        foreach($unUploadedOrders as $order)
        {
            $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($order) {
                $query->select('conversation')
                ->from('facebook_messages')
                ->where('message', 'like', '%'.$order->intern_tracking.'%')
                ->groupBy('conversation')
                ->get();
            })->first();
            if($conversation)
                print_r($conversation->facebook_conversation_id);
            else
            {
                $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($order) {
                    $query->select('conversation')
                    ->from('facebook_messages')
                    ->where('message', 'like', '%'.$order->phone.'%')
                    ->groupBy('conversation')
                    ->get();
                })->first();
                if($conversation)
                {
                    echo $conversation->phone.":";
                    print_r($conversation->facebook_conversation_id);
                }
            }

            echo "\n";
        }
    }
}
