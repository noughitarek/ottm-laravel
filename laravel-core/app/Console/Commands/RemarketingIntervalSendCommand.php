<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\RemarketingInterval;
use App\Models\RemarketingIntervalMessages;

class RemarketingIntervalSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remarketing-interval-send';

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
        if(!config('settings.scheduler.remarketing_interval_send'))
            exit;
        $remarketings = RemarketingInterval::where('deleted_at', null)->where('is_active', true)->get();
        foreach ($remarketings as $remarketing) {
            $conversations = $remarketing->Get_Supported_Conversations();
            foreach($conversations[0] as $conversation){
                RemarketingIntervalMessages::create([
                    'remarketing' => $remarketing->id,
                    'facebook_conversation_id' => $conversation->facebook_conversation_id,
                    'last_use' => now(),
                ]);
                $conversation->Remarketing($remarketing);
            }
        }
        
    }
}
