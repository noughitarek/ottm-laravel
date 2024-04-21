<?php

namespace App\Http\Controllers;

use App\Models\Responder;
use App\Models\FacebookPage;
use Illuminate\Http\Request;
use App\Models\MessagesTemplates;
use App\Models\ResponderTemplate;

class ResponderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responders = Responder::where('deleted_at', null)->orderBy('created_at', 'desc')->paginate(20)->onEachSide(2);
        return view('pages.responders.index')->with('responders', $responders);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = FacebookPage::whereNull('expired_at')->where('type', 'business')->get();
        $templates = MessagesTemplates::whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
        return view('pages.responders.create')->with('templates', $templates)->with('pages', $pages);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach($request->pages as $page){
            $responder = Responder::create([
                "name" => $request->input('name'),
                "page" => $page,
            ]);
            foreach($request->input('template') as $order=>$template)
            {
                if($template != null)
                {
                    ResponderTemplate::create([
                        'responder' => $responder->id,
                        'template' => $template,
                        'order' => $order
                    ]);
                }
            }
        }
        return back()->with('success', "Responder message has been created");
    }


    /**
     * Show the form for edit a resource.
     */
    public function edit(Responder $responder)
    {
        $pages = FacebookPage::whereNull('expired_at')->where('type', 'business')->get();
        $templates = MessagesTemplates::whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
        return view('pages.responders.edit')->with('responder', $responder)->with('templates', $templates)->with('pages', $pages);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Responder $responder)
    {
        foreach($request->pages as $page){
            ResponderTemplate::where('responder', $responder->id)->delete();
            $responder->update([
                "name" => $request->input('name'),
                "page" => $page,
            ]);
            foreach($request->input('template') as $order=>$template)
            {
                if($template != null)
                {
                    ResponderTemplate::create([
                        'responder' => $responder->id,
                        'template' => $template,
                        'order' => $order
                    ]);
                }
            }
        }
        return back()->with('success', "Responder message has been updated");
    }
    /**
     * activate the specified resource.
     */
    public function activate(Responder $responder)
    {
        if($responder->is_active){
            return back()->with('error', 'Message already active');
        }
        else
        {
            $responder->update(['is_active'=>true]);
            return back()->with('success', 'Message has been activated successfully');
        }
    }
    
    /**
     * deactivate the specified resource.
     */
    public function deactivate(Responder $responder)
    {
        if(!$responder->is_active){
            return back()->with('error', 'Message already inactive');
        }
        else
        {
            $responder->update(['is_active'=>false]);
            return back()->with('success', 'Message has been deactivated successfully');
        }
    }
    
    public function history(Responder $responder)
    {
        return view('pages.responders.history')->with('responder', $responder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Responder $responder)
    {
        $responder->update(['deleted_at' => now(), 'is_active'=>false]);
        return back()->with('success', "Responder message has been deleted");
    }

}
