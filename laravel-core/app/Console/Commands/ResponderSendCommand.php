<?php

namespace App\Console\Commands;

use App\Models\Responder;
use Illuminate\Console\Command;
use App\Models\ResponderMessage;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;

class ResponderSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:responder-send-command';

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
        if(!config('settings.scheduler.responder_send'))
            exit;
        $responders = Responder::where('deleted_at', null)->where('is_active', true)->get();
        foreach($responders as $responder)
        {
            foreach($responder->Pages() as $page)
            {
                $page->Responder($responder);
            }
            /*
            $conversations = $responder->Get_Supported_Conversations();
            foreach($conversations as $conversation)
            {
                ResponderMessage::create([
                    'responder' => $responder->id,
                    'facebook_conversation_id' => $conversation->facebook_conversation_id,
                    'last_use' => now(),
                ]);
                $conversation = FacebookConversation::where('facebook_conversation_id', $conversation->facebook_conversation_id)->first();
                $conversation->Responder($responder);
            }
            */
        }
    }
}
