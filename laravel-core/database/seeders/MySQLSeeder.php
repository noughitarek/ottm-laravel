<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\FacebookPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MySQLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = DB::connection('mysql_seed')->table('settings')->select('*')->get();
        foreach($settings as $setting){
            try{
                Setting::create((array)$setting);
            }
            catch(\Exception $e)
            {
                echo $e;
            }
        }
        $facebook_pages = DB::connection('mysql_seed')->table('facebook_pages')->select('*')->get();
        foreach($facebook_pages as $facebook_page){
            try{
                FacebookPage::create((array)$facebook_page);
            }
            catch(\Exception $e)
            {
                echo $e;
            }
        }
    }
}
