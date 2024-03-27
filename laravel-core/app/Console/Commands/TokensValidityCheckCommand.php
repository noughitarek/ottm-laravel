<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TokensValidityCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tokens-validity-check-command';

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
        $pages = FacebookPage::where('expired_at', null)->all();
        foreach($pages as $page){
            $page->Validity_Check();
        }
    }
}