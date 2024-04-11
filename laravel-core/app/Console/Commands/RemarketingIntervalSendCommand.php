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
        exit;
        if(!config('settings.scheduler.remarketing_interval_send'))
            exit;
        $remarketings = RemarketingInterval::where('deleted_at', null)->where('is_active', true)->get();
        $now = Carbon::now();
        foreach ($remarketings as $remarketing) {
            $conversations = $remarketing->Get_Supported_Conversations();
            $sendOn = Carbon::createFromTimestamp($now->timestamp - $remarketing->send_after_each);
            if($conversations[1] == 0) 
            {
                RemarketingIntervalMessages::where('remarketing', $remarketing->id)
                ->where('last_use', '<',  $sendOn->toDateTimeString())
                ->update(['deleted_at'=>now()]);
                $conversations = $remarketing->Get_Supported_Conversations();
            }
                
            $total_cnt = RemarketingIntervalMessages::where('remarketing', $remarketing->id)
            ->where('deleted_at', null)->count();
            $total = (int)($conversations[1]/$remarketing->devide_by)+1;
            foreach($conversations[0] as $i=>$conversation){
                if(($i+1)>=$total)
                {
                    exit;
                }
                RemarketingIntervalMessages::create([
                    'remarketing' => $remarketing->id,
                    'facebook_conversation_id' => $conversation->facebook_conversation_id,
                    'last_use' => now(),
                ]);
                
                #$conversation->Remarketing($remarketing);
            }
        }
            /*
            $conversations = $remarketing->Get_Supported_Conversations();
            $total = (int)($conversations[1]/$remarketing->devide_by)+1;
            $conversations = $remarketing->Get_Supported_Conversations($total);
            foreach($conversations[0] as $conversation){
                RemarketingMessages::create([
                    'remarketing' => $remarketing->id,
                    'facebook_conversation_id' => $conversation->facebook_conversation_id,
                    'last_use' => now(),
                ]);
                $conversation->Remarketing($remarketing);
            }
            echo $conversations[1];*/
        
    }
}
