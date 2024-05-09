<?php

namespace App\Console\Commands;

use App\Models\FacebookPage;
use Illuminate\Console\Command;

class TokensValidityCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tokens-validity-check';

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
        if(!config('settings.scheduler.tokens_validity_check'))
            exit;
        $pages = FacebookPage::where('expired_at', null)->get();
        foreach($pages as $page){
            $page->Validity_Check();
        }
    }
}
