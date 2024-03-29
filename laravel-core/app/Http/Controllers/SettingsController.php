<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.settings');
    }
    public function edit(Request $request)
    {
        foreach($request->all() as $path=>$content)
        {
            if($path == "_token") continue;
            $setting = Setting::where('path', $path)->first();
            if(!$setting){
                $setting = Setting::create([
                    'path' => $path,
                    'content' => $content,
                ]);
            }else{
                $setting->update(['content'=>$content]);
            }
        }
        return back()->with('succss', 'Settings has been updated successfully');
    }
}
