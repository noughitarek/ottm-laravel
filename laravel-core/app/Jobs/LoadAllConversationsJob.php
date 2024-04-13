<?php

namespace App\Jobs;

use App\Models\FacebookPage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LoadAllConversationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        foreach($pages as $page){
            if($page->facebook_page_id != '110505321853990')continue;
            echo $page->facebook_page_id."\n";
            $page->Get_Conversations(true);
        }
    }
}
