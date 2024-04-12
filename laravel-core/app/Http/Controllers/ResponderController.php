<?php

namespace App\Http\Controllers;

use App\Models\Responder;
use App\Models\FacebookPage;
use Illuminate\Http\Request;

class ResponderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        return view('pages.responder')->with('pages', $pages);
    }

    /**
     * Update the specified resource in storage.
     */
    public function edit(Request $request)
    {
        foreach($request->all() as $page=>$message)
        {
            if($page == "_token") continue;
            $responder = Responder::where('page', $page)->first();
            if(!$responder){
                $responder = Responder::create([
                    'page' => $page,
                    'message' => $message,
                ]);
            }else{
                $responder->update(['message'=>$message]);
            }
        }
        return back()->with('succss', 'Messages has been updated successfully');
    }
}
