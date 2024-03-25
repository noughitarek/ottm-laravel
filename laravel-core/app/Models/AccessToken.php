<?php

namespace App\Models;

use App\Models\FacebookUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessToken extends Model
{
    use HasFactory;
    protected $fillable = ["content", "type"];
    public static function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function Check()
    {
        $response = Http::get('https://graph.facebook.com/me/', [
            'access_token' => $this->content,
            'fields' => 'id,name'
        ]);
        $userData = $response->json();
        return true;
    }

    public function Page()
    {
        $accessToken = AccessToken::where('type', 'business')->where('expired_at', null)->first();
        if(!$accessToken)
        {
            $response = Http::get('https://graph.facebook.com/me/accounts', [
                'access_token' => $this->content,
            ]);
            
            $fb_user = FacebookUser::where("facebook_user_id", $response->json()["data"][0]["id"])->first();
            if(!$fb_user)
            {
                $fb_user = FacebookUser::create([
                    'facebook_user_id' => $response->json()["data"][0]["id"],
                    'name' => $response->json()["data"][0]["name"],
                    'can_reply' => true
                ]);
            }
            return AccessToken::create([
                "content" => $response->json()["data"][0]["access_token"],
                "type" => "business"
            ]);
        }
        return $accessToken; 
    }

    public function Get_Conversations()
    {
        $response = Http::get('https://graph.facebook.com/me/conversations', [
            'access_token' => $this->content,
            'limit' => 100,
            'fields' => 'can_reply,senders,messages.limit(1000){id,message,created_time,from,to}',
        ]);
        foreach($response->json()['data'] as $conversation){
            $fb_user = FacebookUser::where("facebook_user_id", $conversation["senders"]["data"][0]["id"])->first();
            if(!$fb_user)
            {
                $fb_user = FacebookUser::create([
                    'facebook_user_id' => $conversation["senders"]["data"][0]["id"],
                    'name' => $conversation["senders"]["data"][0]["name"],
                    'email' => $conversation["senders"]["data"][0]["email"],
                    'can_reply' => $conversation["can_reply"]==1?true:false
                ]);
            }
            foreach($conversation['messages']['data'] as $message){
                $fb_message = Message::where('message_id', $message['id'])->first();
                if(!$fb_message){
                    Message::create([
                        'message_id' => $message['id'],
                        'content' => $message['message'],
                        'created_at' => $message['created_time'],
                        'sented_by' => $message['from']['id'],
                        'sented_to' => $message['to']['data'][0]['id'],
                    ]);
                }
            }
        }
    }

    public function Get_User()
    {
        $response = Http::get('https://graph.facebook.com/me', [
            'access_token' => $this->content,
            'fields' => 'id,name,picture'
        ]);
        return $response->json();
    }
}
