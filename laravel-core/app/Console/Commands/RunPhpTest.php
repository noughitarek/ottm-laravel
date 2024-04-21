<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RemarketingInterval;
use App\Models\FacebookConversation;

class RunPhpTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-php-test';

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
        $conv = FacebookConversation::where('facebook_conversation_id', 't_2904646829674985')->first();
        $remarketing  = RemarketingInterval::find(25);
        $conv->RemarketingInterval($remarketing);
    }
}
