<?php

namespace App\Http\Controllers;

use App\Models\Remarketing;
use App\Models\FacebookPage;
use App\Models\RemarketingMessages;
use App\Models\FacebookConversation;
use App\Http\Requests\StoreRemarketingRequest;
use App\Http\Requests\UpdateRemarketingRequest;

class RemarketingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $remarketings = Remarketing::where('deleted_at', null)->get(); 
        return view('pages.remarketing.remarketing')->with('remarketings', $remarketings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        return view('pages.remarketing.create')->with('pages', $pages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRemarketingRequest $request)
    {
        $photos = [];
        $videos = [];
        if($request->hasFile('photos'))
        {
            foreach($request->file('photos') as $photo){
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('storage/remarketing'), $filename);
                $photos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->hasFile('videos'))
        {
            foreach($request->file('videos') as $video){
                $filename = time() . '_' . $video->getClientOriginalName();
                $video->move(public_path('storage/remarketing'), $filename);
                $videos[] = asset('storage/remarketing/' . $filename);
            }
        }

        foreach($request->pages as $page){
            $remarketing = Remarketing::create([
                "name" => $request->input('name'),
                "facebook_page_id" => $page,
                "send_after" => $request->input('send_after')*$request->input('time_unit'),
                "last_message_from" => $request->input('last_message_from'),
                "make_order" => $request->input('make_order'),
                "since" => $request->input('since'),
                "photos" => implode(',', $photos),
                "video" => implode(',', $videos),
                "message" => $request->input('message')
            ]);
            
            $conversations = FacebookConversation::all();
            foreach($conversations as $conversation){
                RemarketingMessages::create([
                    'remarketing' => $remarketing->id,
                    'facebook_conversation_id' => $conversation->facebook_conversation_id,
                    'last_use' => now(),
                ]);
            }
        }
        return back()->with('success', "Remarketing message has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Remarketing $remarketing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Remarketing $remarketing)
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        return view('pages.remarketing.edit')->with('remarketing', $remarketing)->with('pages', $pages);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRemarketingRequest $request, Remarketing $remarketing)
    {
        $photos = [];
        $videos = [];
        if($request->hasFile('photos'))
        {
            foreach($request->file('photos') as $photo){
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('storage/remarketing'), $filename);
                $photos[] = asset('storage/remarketing/' . $filename);
            }
        }
        if($request->hasFile('videos'))
        {
            foreach($request->file('videos') as $video){
                $filename = time() . '_' . $video->getClientOriginalName();
                $video->move(public_path('storage/remarketing'), $filename);
                $videos[] = asset('storage/remarketing/' . $filename);
            }
        }

        foreach($request->pages as $page){
            if($page == $remarketing->facebook_page_id)
            {
                $remarketing->update([
                    "name" => $request->input('name'),
                    "facebook_page_id" => $page,
                    "send_after" => $request->input('send_after')*$request->input('time_unit'),
                    "last_message_from" => $request->input('last_message_from'),
                    "make_order" => $request->input('make_order'),
                    "since" => $request->input('since'),
                    "photos" => implode(',', array_merge($photos, $request->input('oldPhotos'))),
                    "video" => implode(',',array_merge($videos, $request->input('oldVideos'))),
                    "message" => $request->input('message')
                ]);
            }
            else
            {
                $remarketing = Remarketing::create([
                    "name" => $request->input('name'),
                    "facebook_page_id" => $page,
                    "send_after" => $request->input('send_after')*$request->input('time_unit'),
                    "last_message_from" => $request->input('last_message_from'),
                    "make_order" => $request->input('make_order'),
                    "since" => $request->input('since'),
                    "photos" => implode(',', array_merge($photos, $request->input('oldPhotos'))),
                    "video" => implode(',',array_merge($videos, $request->input('oldVideos'))),
                    "message" => $request->input('message')
                ]);
            }
        }
        return back()->with('success', "Remarketing message has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Remarketing $remarketing)
    {
        $remarketing->update(['deleted_at' => now()]);
        return back()->with('success', "Remarketing message has been deleted");
    }
}
