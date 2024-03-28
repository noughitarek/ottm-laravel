<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
use Illuminate\Http\Request;
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
        FacebookPage::where('type', 'user')->update(['expired_at' => now()]);

        FacebookPage::create(array(
            "facebook_page_id" => (string)$user->id,
            "name" => $user->name,
            "access_token" => $user->token,
            'type' => 'user',
        ));
        FacebookPage::where('type', 'business')->update(['expired_at' => now()]);
        $pages = FacebookPage::Get_Pages();
        return redirect('/');
    }

}
