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
        $remarketings = Remarketing::where('deleted_at', null)->where('is_active', true)->get();
        foreach ($remarketings as $remarketing) {
            $conversations = $remarketing->Get_Supported_Conversations();
            foreach($conversations[0] as $conversation){
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
