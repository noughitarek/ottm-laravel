<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
use Illuminate\Http\Request;
use App\Jobs\LoadAllConversationsJob;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;

class FacebookPageController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->scopes(['email', 'pages_show_list', 'pages_messaging'])->redirect();
    }
    
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Facebook authentication failed.');
        }
        $fb_user = FacebookPage::where("facebook_page_id", (string)$user->id)->first();
        if(!$fb_user)
        {
            FacebookPage::create(array(
                "facebook_page_id" => (string)$user->id,
                "name" => $user->name,
                "access_token" => $user->token,
                'type' => 'user',
                'expired_at' => null
            ));
        }
        else
        {
            $fb_user->update(array(
                "facebook_page_id" => (string)$user->id,
                "name" => $user->name,
                "access_token" => $user->token,
                'type' => 'user',
                'expired_at' => null
            ));
        }
        
        $pages = FacebookPage::Get_Pages();
        return redirect('/');
    }

    public function load_conversations()
    {
        LoadAllConversationsJob::dispatch();
        return back()->with('success', 'The facebook conversation is beign loaded');
    }

}
