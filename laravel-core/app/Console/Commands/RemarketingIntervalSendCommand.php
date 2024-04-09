<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    }
}
