<?php

namespace App\Models;

use App\Models\FacebookPage;
use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use App\Models\FacebookConversation;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookPage extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'access_token', 'type', 'expired_at'];

    public function Validity_Check()
    {
        try
        {
            $response = Http::get('https://graph.facebook.com/v19.0/me', [
                'access_token' => $this->access_token,
            ]);
            if(isset($response['error']) && isse($response['error']['code']) && $response['error']['code']==190)
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

    public function Get_Conversations()
    {
        if($this->type != 'business')
        {
            return false;
        }
        try
        {
            $response = Http::get('https://graph.facebook.com/me/conversations', [
                'access_token' => $this->access_token,
                'limit' => 100,
                'fields' => 'can_reply,senders,messages.limit(1000){id,message,created_time,from,to}',
            ]);
            if ($response->successful())
            {
                $responseData = $response->json();
                if(isset($responseData['data']))
                {
                    foreach($responseData['data'] as $conversation)
                    {
                        $user = FacebookUser::find($conversation["senders"]["data"][0]["id"]);
                        if(!$user)
                        {
                            $user = FacebookUser::create([
                                'id' => $conversation["senders"]["data"][0]["id"],
                                'name' => $conversation["senders"]["data"][0]["name"],
                                'email' => $conversation["senders"]["data"][0]["email"]
                            ]);
                        }
                        FacebookConversation::create([
                            'id' => $conversation['id'],
                            'page' => $this->id,
                            'user' => $user->id,
                            'can_reply' => $conversation["can_reply"]==1?true:false
                        ]);

                        foreach($conversation['messages']['data'] as $message)
                        {
                            $fb_message = FacebookMessage::find($message['id'])->first();
                            if(!$fb_message){
                                FacebookMessage::create([
                                    'id' => $message['id'],
                                    'message' => $message['message'],
                                    'sented_from' => $message['from']['id']==$this->id?'page':'user',
                                    'conversation' => $conversation['id'],
                                    'created_at' => $message['created_time']
                                ]);
                            }
                        }
                    }
                    return true;
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
        catch (\Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }
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
                            FacebookPage::create([
                                "id" => $pageVal['id'],
                                "name" => $pageVal['name'],
                                "access_token" => $pageVal['access_token'],
                                'type' => 'business',
                            ])->Get_Conversations();
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
}
