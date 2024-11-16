<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\FacebookPage;
use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use Illuminate\Console\Command;
use App\Models\FacebookConversation;
use Illuminate\Support\Facades\Http;

class GetAllConversationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-all-conversations';

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
        $this->start();
        $this->start();
        $this->start();
        $this->start();
    }
    public function start()
    {
        $pages = FacebookPage::where('id', 2)->where('expired_at', null)->where('type', 'business')->get();
        foreach($pages as $page)
        {
            $nextpage = Setting::where('path' , 'next_page_'.$page->facebook_page_id)->first();
            if($nextpage)
            {
                $data= [
                    'access_token' => $page->access_token,
                    'limit' => config('settings.limits.conversations'),
                    'fields' => 'can_reply,senders,messages.limit('.config('settings.limits.message_per_conversation').'){id,message,created_time,from,to}',
                    'after' => $nextpage->content
                ];
                echo $nextpage;
            }
            else
            {
                $data= [
                    'access_token' => $page->access_token,
                    'limit' => config('settings.limits.conversations'),
                    'fields' => 'can_reply,senders,messages.limit('.config('settings.limits.message_per_conversation').'){id,message,created_time,from,to}'
                ];
            }
            try
            {
                $response = Http::timeout(60)->get('https://graph.facebook.com/me/conversations', $data);
                if ($response->successful())
                {
                    $responseData = $response->json();
                    if(isset($responseData['data']))
                    {
                        foreach($responseData['data'] as $conversation)
                        {
                            $user = FacebookUser::where('facebook_user_id', $conversation["senders"]["data"][0]["id"])->first();
                            if(!$user)
                            {
                                $user = FacebookUser::create([
                                    'facebook_user_id' => (string)$conversation["senders"]["data"][0]["id"],
                                    'name' => $conversation["senders"]["data"][0]["name"],
                                    'email' => $conversation["senders"]["data"][0]["email"]
                                ]);
                            }
                            $cnversation = FacebookConversation::where('facebook_conversation_id', $conversation['id'])->first();
                            if(!$cnversation)
                            {
                                $cnversation = FacebookConversation::create([
                                    'facebook_conversation_id' => (string)$conversation['id'],
                                    'page' => (string)$page->facebook_page_id,
                                    'user' => (string)$conversation["senders"]["data"][0]["id"],
                                    'can_reply' => $conversation["can_reply"]==1?true:false,
                                ]);
                            }

                            foreach($conversation['messages']['data'] as $message)
                            {
                                $fb_message = FacebookMessage::where('facebook_message_id', $message['id'])->first();
                                if(!$fb_message){
                                    $fb_message = FacebookMessage::create([
                                        'facebook_message_id' => (string)$message['id'],
                                        'message' => $message['message'],
                                        'sented_from' => $message['from']['id']==$page->facebook_page_id?'page':'user',
                                        'conversation' => (string)$conversation['id'],
                                        'created_at' => $message['created_time']
                                    ]);
                                }
                                if($cnversation->started_at == null || $cnversation->started_at > $fb_message->created_at)
                                {
                                    $cnversation->started_at = $fb_message->created_at;
                                }
                                if($cnversation->ended_at == null ||$cnversation->ended_at < $fb_message->created_at)
                                {
                                    $cnversation->last_from = $message['from']['id']==$page->facebook_page_id?'page':'user';
                                    $cnversation->ended_at = $fb_message->created_at;
                                }
                                $cnversation->make_order = false;
                                if($message['from']['id']==$page->facebook_page_id && $cnversation->last_from_page_at < $fb_message->created_at)
                                {
                                    $cnversation->last_from_page_at = $fb_message->created_at;
                                }
                                if($message['from']['id']!=$page->facebook_page_id && $cnversation->last_from_user_at < $fb_message->created_at)
                                {
                                    $cnversation->last_from_user_at = $fb_message->created_at;
                                }
                                $cnversation->save();
                            }
                        }
                        echo 'Total: '.count($responseData['data'])."\n";
                    }
                    else
                    {
                        echo 'No data found in the response.';
                    }
                }
                else
                {
                    echo 'Error occurred while fetching data from Facebook API.';
                }
                if(isset($responseData['paging']) && isset($responseData['paging']['cursors']) && isset($responseData['paging']['cursors']['after']))
                {
                    $setting = Setting::where('path' ,'next_page_'.$page->facebook_page_id)->first();
                    if(!$setting)
                    {
                        $setting = Setting::create([
                            'path' => 'next_page_'.$page->facebook_page_id,
                            'content' => $responseData['paging']['cursors']['after'],
                        ]);
                    }
                    $setting->update(['content'=>$responseData['paging']['cursors']['after']]);
                }
                else
                {
                    Setting::where('path' ,'next_page_'.$page->facebook_page_id)->delete();
                }
            }
            catch (\Exception $e)
            {
                echo 'Error: ' . $e->getMessage();
            }
        }
    }
}
