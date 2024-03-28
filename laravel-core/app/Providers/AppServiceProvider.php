<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\FacebookPage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try{
            if(Schema::hasTable('settings'))
            {
                $settings = Setting::all();
                foreach($settings as $setting){
                    config([str_replace('-', '.', $setting->path) => $setting->content]);
                }
            }
            if(Schema::hasTable('facebook_pages'))
            {
                $user = FacebookPage::where('expired_at', null)->where('type', 'user')->first();
                $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
                config(["settings.active_user"=> $user]);
                config(["settings.active_pages"=> $pages]);
            }
        }
        catch(\Exception $e)
        {
            echo 'err';
        }
    }
}
