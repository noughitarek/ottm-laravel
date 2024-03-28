<?php

namespace App\Console\Commands;

use App\Models\FacebookPage;
use Illuminate\Console\Command;

class GetConversationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-conversations';

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
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        foreach($pages as $page){
            $page->Get_Conversations();
        }
    }
}
