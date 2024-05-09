<?php

namespace App\Models;

use App\Models\Responder;
use App\Models\FacebookPage;
use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use App\Models\MessagesTemplates;
use App\Models\ResponderTemplate;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use App\Models\RemarketingIntervalMessages;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookPage extends Model
{
    use HasFactory;
    protected $fillable = ['facebook_page_id', 'name', 'access_token', 'type', 'expired_at'];

    public function Orders()
    {
        $conversations = FacebookConversation::where('page', $this->facebook_page_id)->get();
        $total = 0;
        foreach($conversations as $conversation)
        {
            $total += $conversation->Orders();
        }
        return $total;
    }
    public function Validity_Check()
    {
        try
        {
            $response = Http::get('https://graph.facebook.com/v19.0/me', [
                'access_token' => $this->access_token,
            ]);
            if(isset($response['error']) && isset($response['error']['code']) && $response['error']['code']==190)
            {
                $this->expired_at = now();
                $this->save();
            }
        }
        catch(\Exception $e)
        {
            $this->expired_at = now();
            $this->save();
        }
    }
    public function Get_Conversations_Page($data, $page=null, $all=false)
    {
        $setting = Setting::where('path' ,'next_page')->first();
        if($setting)
        {
            $page = $setting->content;
        }
        if($page!=null)
        {
            $data = array_merge($data, ['after'=>$page]);
        }
        try
        {
            $response = Http::timeout(240)->get('https://graph.facebook.com/me/conversations', $data);
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
                                'page' => (string)$this->facebook_page_id,
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
                                    'sented_from' => $message['from']['id']==$this->facebook_page_id?'page':'user',
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
                                $cnversation->last_from = $message['from']['id']==$this->facebook_page_id?'page':'user';
                                $cnversation->ended_at = $fb_message->created_at;
                            }
                            $cnversation->make_order = false;
                            if($message['from']['id']==$this->facebook_page_id && $cnversation->last_from_page_at < $fb_message->created_at)
                            {
                                $cnversation->last_from_page_at = $fb_message->created_at;
                            }
                            if($message['from']['id']!=$this->facebook_page_id && $cnversation->last_from_user_at < $fb_message->created_at)
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
                if($all && isset($responseData['paging']) && isset($responseData['paging']['cursors']) && isset($responseData['paging']['cursors']['after']))
                {
                    $setting = Setting::where('path' ,'next_page')->first();
                    if(!$setting)
                    {
                        $setting = Setting::create([
                            'path' => 'next_page',
                            'content' => $responseData['paging']['cursors']['after'],
                        ]);
                    }
                    $setting->update(['content'=>$responseData['paging']['cursors']['after']]);

                    $this->Get_Conversations_Page($data, $responseData['paging']['cursors']['after'], $all);
                }
                else
                {
                    Setting::where('path' ,'next_page')->delete();
                }
            }
            else
            {
                echo $this->name.": ";
                echo 'Error occurred while fetching data from Facebook API.';
                $this->Get_Conversations_Page($data, $page, $all);
            }
        }
        catch (\Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public function Get_Conversations($all=false)
    {
        if($this->type != 'business')
        {
            return false;
        }
        try
        {
            $data= [
                'access_token' => $this->access_token,
                'limit' => config('settings.limits.conversations'),
                'fields' => 'can_reply,senders,messages.limit('.config('settings.limits.message_per_conversation').'){id,message,created_time,from,to}',
            ];
            $this->Get_Conversations_Page($data, null, $all);
        }
        catch (\Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function Messages_Count()
    {
        $query = "SELECT SUM(message_count) AS total
            FROM (
                SELECT fc.facebook_conversation_id, COUNT(fm.id) AS message_count
                FROM facebook_messages fm
                JOIN facebook_conversations fc ON fm.conversation = fc.facebook_conversation_id
                WHERE fc.page = :page_id
                GROUP BY fc.facebook_conversation_id
            ) AS subquery_alias";

        $count = DB::select($query, ['page_id' => $this->facebook_page_id]);
        
        return $count[0]->total;
    }
    public function Conversations_Count()
    {
        $query = "SELECT count(id) AS total FROM facebook_conversations WHERE page = :page_id;";

        $count = DB::select($query, ['page_id' => $this->facebook_page_id]);
        
        return $count[0]->total;
    }
    public static function Get_Pages()
    {
        $active_user = FacebookPage::whereNull('expired_at')->where('type', 'user')->first();
        if ($active_user)
        {
            try
            {
                $response = Http::get('https://graph.facebook.com/v19.0/me/accounts', [
                    'access_token' => $active_user->access_token,
                ]);
                if ($response->successful())
                {
                    $responseData = $response->json();
                    if (isset($responseData['data']))
                    {
                        FacebookPage::where('type', 'business')->update(['expired_at'=> now()]);
                        foreach ($responseData['data'] as $pageVal) {
                            $fb_page = FacebookPage::where('facebook_page_id', (string)$pageVal['id'])->first();
                            if(!$fb_page)
                            {
                                FacebookPage::create([
                                    'facebook_page_id' => (string)$pageVal['id'],
                                    "name" => $pageVal['name'],
                                    "access_token" => $pageVal['access_token'],
                                    'type' => 'business',
                                ])->Get_Conversations();
                            }
                            else
                            {
                                $fb_page->update([
                                    'facebook_page_id' => (string)$pageVal['id'],
                                    "name" => $pageVal['name'],
                                    "access_token" => $pageVal['access_token'],
                                    'type' => 'business',
                                    'expired_at' => null
                                ]);   
                            }
                        }
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
            }
            catch(\Exception $e)
            {
                echo 'Error: ' . $e->getMessage();
            }
        }
        else
        {
            echo 'No active user';
        }

    }
    public function Send_Message($to, $message)
    {
        try
        {
            $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                'access_token' => $this->access_token,
                'messaging_type' => 'MESSAGE_TAG',
                'recipient' => ['id' => $to],
                'message' => ['text' => $message],
                'tag' => 'ACCOUNT_UPDATE'
            ]);
            return true;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
    
    public function Remarketing($to, $remarketing)
    {
        if($remarketing->template == null)
        {
            try
            {
                if($remarketing->message != null && $remarketing->message != "")
                {
                    $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                        'access_token' => $this->access_token,
                        'messaging_type' => 'MESSAGE_TAG',
                        'recipient' => ['id' => $to],
                        'message' => ['text' => $remarketing->message],
                        'tag' => 'ACCOUNT_UPDATE'
                    ]);
                }
                foreach(explode(",", $remarketing->photos) as $photo)
                {
                    if($photo != null && $photo != "")
                    {
                        try
                        {
                            $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                                'access_token' => $this->access_token,
                                'messaging_type' => 'MESSAGE_TAG',
                                'recipient' => ['id' => $to],
                                'message' => [
                                    'attachment' => [
                                        'type' => "image",
                                        "payload" => [
                                            'url' => $photo
                                        ],
                                    ]
                                ],
                                'tag' => 'ACCOUNT_UPDATE'
                            ]);
                        }
                        catch(\Exception $e)
                        {
                            echo $e;
                        }
                    }
                }
                foreach(explode(",", $remarketing->photos) as $photo)
                {
                    if($remarketing->video != null && $remarketing->video != "")
                    {
                        try
                        {
                            $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                                'access_token' => $this->access_token,
                                'messaging_type' => 'MESSAGE_TAG',
                                'recipient' => ['id' => $to],
                                'message' => [
                                    'attachment' => [
                                        'type' => "video",
                                        "payload" => [
                                            'url' => $remarketing->video
                                        ],
                                    ]
                                ],
                                'tag' => 'ACCOUNT_UPDATE'
                            ]);
                        }
                        catch(\Exception $e)
                        {
                            echo $e;
                        }
                    }
                }
                return true;
            }
            catch(\Exception $e)
            {
                echo $e;
            }
        }
        else
        {
            foreach($remarketing->Template()->Asset() as $asset)
            {
                if($asset['type'] == "message")
                {
                    $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                        'access_token' => $this->access_token,
                        'messaging_type' => 'MESSAGE_TAG',
                        'recipient' => ['id' => $to],
                        'message' => ['text' => $asset['content']],
                        'tag' => 'ACCOUNT_UPDATE'
                    ]);
                }
                else
                {
                    
                    try
                    {
                        $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                            'access_token' => $this->access_token,
                            'messaging_type' => 'MESSAGE_TAG',
                            'recipient' => ['id' => $to],
                            'message' => [
                                'attachment' => [
                                    'type' => $asset['type'],
                                    "payload" => [
                                        'url' => $asset['content'],
                                    ],
                                ]
                            ],
                            'tag' => 'ACCOUNT_UPDATE'
                        ]);
                    }
                    catch(\Exception $e)
                    {
                        echo $e;
                    }
                }
            }
        }
    }
    
    public function RemarketingInterval($to, $remarketing)
    {
        foreach($remarketing->Get_Template()->Template()->Asset() as $asset)
        {
            if($asset['type'] == "message")
            {
                $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                    'access_token' => $this->access_token,
                    'messaging_type' => 'MESSAGE_TAG',
                    'recipient' => ['id' => $to],
                    'message' => ['text' => $asset['content']],
                    'tag' => 'ACCOUNT_UPDATE'
                ]);
            }
            else
            {
                
                try
                {
                    $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                        'access_token' => $this->access_token,
                        'messaging_type' => 'MESSAGE_TAG',
                        'recipient' => ['id' => $to],
                        'message' => [
                            'attachment' => [
                                'type' => $asset['type'],
                                "payload" => [
                                    'url' => $asset['content'],
                                ],
                            ]
                        ],
                        'tag' => 'ACCOUNT_UPDATE'
                    ]);
                }
                catch(\Exception $e)
                {
                    echo $e;
                }
            }
        }
    }
    public function Template()
    {
        return Template::find($this->Responder()->id);
    }
    public function Responder($to, $responder)
    {
        $template = ResponderTemplate::where('responder', $responder->id)
        ->whereNotIn('template', function ($query) {
            $query->select('template')
                ->from('responder_messages')
                ->where('responder', '=', DB::raw('responder_templates.responder'));
        })->first()
        ??
        ResponderMessage::where('responder', $responder->id)
        ->selectRaw('template, COUNT(template) AS count_template')
        ->groupBy('template')
        ->orderByRaw('count_template ASC, MIN(created_at) ASC')
        ->first();
        $template = MessagesTemplates::find($template->template);
        foreach($template->Asset() as $asset)
        {
            if($asset['type'] == "message")
            {
                $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                    'access_token' => $this->access_token,
                    'messaging_type' => 'MESSAGE_TAG',
                    'recipient' => ['id' => $to],
                    'message' => ['text' => $asset['content']],
                    'tag' => 'ACCOUNT_UPDATE'
                ]);
            }
            else
            {
                
                try
                {
                    $response = Http::post('https://graph.facebook.com/v19.0/me/messages', [
                        'access_token' => $this->access_token,
                        'messaging_type' => 'MESSAGE_TAG',
                        'recipient' => ['id' => $to],
                        'message' => [
                            'attachment' => [
                                'type' => $asset['type'],
                                "payload" => [
                                    'url' => $asset['content'],
                                ],
                            ]
                        ],
                        'tag' => 'ACCOUNT_UPDATE'
                    ]);
                }
                catch(\Exception $e)
                {
                    echo $e;
                }
            }
        }
        ResponderMessage::where('responder', $responder->id)
        ->where('facebook_conversation_id',
            FacebookConversation::where('user', $to)->first()->facebook_conversation_id
        )
        ->update(['template' => $template->id]);
    }
}
