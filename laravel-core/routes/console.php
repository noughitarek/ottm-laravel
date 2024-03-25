<?php

use App\Models\AccessToken;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('refresh-conversations', function () {
    $access_token = AccessToken::where("type", "user")->where("expired_at", null)->first();
    if (!$access_token) {
        echo 'no access token';
        exit;
    }else{
        if(!$access_token->Check()){
            $access_token->expired_at = Date::now();
            $access_token->save();
            echo 'expired access token';
            exit;
        }
    }
    $access_token->Page()->Get_User();
    echo "Conversations has been refreshed\n";
})->hourly();
