<?php

namespace App\Http\Controllers;

use App\Models\Remarketing;
use App\Models\FacebookPage;
use App\Models\MessagesTemplates;
use App\Models\RemarketingCategory;
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
        $categories = RemarketingCategory::where('deleted_at', null)->where('parent', null)->get();
        return view('pages.remarketing.categories')->with('categories', $categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function category(RemarketingCategory $category)
    {
        
        $categories = RemarketingCategory::where('deleted_at', null)
        ->where('parent', $category->id)
        ->whereIn('id', Remarketing::where('deleted_at', null)->pluck('category'))
        ->get();
        return view('pages.remarketing.sub_categories')->with('categories', $categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function sub_category(RemarketingCategory $category)
    {
        $remarketings = Remarketing::where('deleted_at', null)->where('category', $category->id)->get();
        foreach($remarketings as $remarketing){
            break;

            if($remarketing->is_active){
                '<form method="POST" action="'.route('remarketing_deactivate', $remarketing->id).'">
                '.$remarketing->name.'
                ' . csrf_field() . '
                ' . method_field('PUT') . '
                    <button type="submit" class="btn btn-primary">Deactivate</button>
                </form>';

                "<br>";
            }
        }
        return view('pages.remarketing.remarketing')->with('remarketings', $remarketings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        $templates = MessagesTemplates::where('deleted_at', null)->get();
        $categories = RemarketingCategory::where('deleted_at', null)->where('parent', '!=', null)->get();
        return view('pages.remarketing.create')->with('pages', $pages)->with('templates', $templates)->with('categories', $categories);
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
                #"photos" => implode(',', $photos),
                #"video" => implode(',', $videos),
                #"message" => $request->input('message'),
                "expire_after" => $request->input('expire_after')*$request->input('expire_time_unit'),
                "resend_after" => $request->input('resend_after')!=""?$request->input('resend_after')*$request->input('resend_time_unit'):null,
                'start_time'=> $request->input('start_time'),
                'end_time'=> $request->input('end_time'),
                'template' => $request->input('template'),
                'category' => $request->input('category'),
            ]);
        }
        return back()->with('success', "Remarketing message has been created");
    }

    public function category_deactivate(RemarketingCategory $category)
    {
        foreach($category->Remarketings() as $remarketing)
        {
            $this->deactivate_store($remarketing);
        }
    }
    public function category_activate(RemarketingCategory $category)
    {
        foreach($category->Remarketings() as $remarketing)
        {
            $this->activate_store($remarketing);
        }
    }


    /**
     * activate the specified resource.
     */
    public function activate(Remarketing $remarketing)
    {
        if($remarketing->is_active)
        {
            return redirect()->route('remarketing_sub_category', $remarketing->category)->with('success', session('success'))->with('error', session('error'));
            return redirect()->route('remarketing')->with('success', session('success'))->with('error', session('error'));
        }
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
     * deactivate the specified resource.
     */
    public function deactivate_store(Remarketing $remarketing)
    {
        if(!$remarketing->is_active){
            return back()->with('error', 'Message already inactive');
        }
        else
        {
            $remarketing->update(['is_active'=>false]);
            return back()->with('success', 'Message has been deactivated successfully');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Remarketing $remarketing)
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        $templates = MessagesTemplates::where('deleted_at', null)->get();
        $categories = RemarketingCategory::where('deleted_at', null)->where('parent', '!=', null)->get();
        return view('pages.remarketing.edit')->with('remarketing', $remarketing)->with('pages', $pages)->with('templates', $templates)->with('categories', $categories);
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
                    #"photos" => implode(',', $photos),
                    #"video" => implode(',', $videos),
                    #"message" => $request->input('message'),
                    "expire_after" => $request->input('expire_after')*$request->input('expire_time_unit'),
                    "resend_after" => $request->input('resend_after')!=""?$request->input('resend_after')*$request->input('resend_time_unit'):null,
                    'start_time'=> $request->input('start_time'),
                    'end_time'=> $request->input('end_time'),
                    'template' => $request->input('template'),
                    'category' => $request->input('category'),
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
                    #"photos" => implode(',', implode(',', $photos)),
                    #"video" => implode(',',implode(',', $videos)),
                    #"message" => $request->input('message'),
                    "expire_after" => $request->input('expire_after')*$request->input('expire_time_unit'),
                    "resend_after" => $request->input('resend_after')!=""?$request->input('resend_after')*$request->input('resend_time_unit'):null,
                    'start_time'=> $request->input('start_time'),
                    'end_time'=> $request->input('end_time'),
                    'template' => $request->input('template'),
                    'category' => $request->input('category'),
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
        $remarketing->update(['deleted_at' => now(), 'is_active'=>false]);
        return back()->with('success', "Remarketing message has been deleted");
    }
    
    public function history(Remarketing $remarketing)
    {
        return view('pages.remarketing.history')->with('remarketing', $remarketing);
    }
}
