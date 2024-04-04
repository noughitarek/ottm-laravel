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
                "message" => $request->input('message'),
                "expire_after" => $request->input('expire_after')*$request->input('expire_time_unit'),
            ]);
        }
        return back()->with('success', "Remarketing message has been created");
    }

    /**
     * activate the specified resource.
     */
    public function activate(Remarketing $remarketing)
    {
        return view('pages.remarketing.activate')->with('remarketing', $remarketing);
    }

    /**
     * activate the specified resource.
     */
    public function activate_store(Remarketing $remarketing)
    {
        if($remarketing->is_active){
            return back()->with('error', 'Message already active');
        }
        else
        {
            $remarketing->update(['is_active'=>true]);
            return back()->with('success', 'Message has been activated successfully');
        }
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
        if($request->has('oldPhotos')){
            foreach($request->oldPhotos as $oldPhoto){
                $photos[] = $oldPhoto;
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
        if($request->has('oldVideos')){
            foreach($request->oldVideos as $oldVideo){
                $videos[] = $oldVideo;
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
<<<<<<< HEAD
                    "photos" => implode(',', array_merge($photos, $request->input('oldPhotos'))),
                    "video" => implode(',',array_merge($videos, $request->input('oldVideos'))),
                    "message" => $request->input('message'),
                    "expire_after" => $request->input('expire_after')*$request->input('expire_time_unit'),
=======
                    "photos" => implode(',', $photos),
                    "video" => implode(',', $videos),
                    "message" => $request->input('message')
>>>>>>> 2f426b795007b5d04ee13212847289f63e3423de
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
                    "message" => $request->input('message'),
                    "expire_after" => $request->input('expire_after')*$request->input('expire_time_unit'),
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
