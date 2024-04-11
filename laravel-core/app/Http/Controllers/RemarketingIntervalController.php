<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
use App\Models\MessagesTemplates;
use App\Models\RemarketingCategory;
use App\Models\RemarketingInterval;
use App\Http\Requests\StoreRemarketingIntervalRequest;
use App\Http\Requests\UpdateRemarketingIntervalRequest;

class RemarketingIntervalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = RemarketingCategory::where('deleted_at', null)->where('parent', null)->get();
        return view('pages.remarketing2.categories')->with('categories', $categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function category(RemarketingCategory $category)
    {
        $categories = RemarketingCategory::where('deleted_at', null)
        ->whereIn('id', RemarketingInterval::where('deleted_at', null)
        ->pluck('category'))
        ->get();
        return view('pages.remarketing2.sub_categories')->with('categories', $categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function sub_category(RemarketingCategory $category)
    {
        $remarketings = RemarketingInterval::where('deleted_at', null)->where('category', $category->id)->get(); 
        return view('pages.remarketing2.remarketing')->with('remarketings', $remarketings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        $templates = MessagesTemplates::where('deleted_at', null)->get();
        $categories = RemarketingCategory::where('deleted_at', null)->get();
        return view('pages.remarketing2.create')->with('pages', $pages)->with('templates', $templates)->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRemarketingIntervalRequest $request)
    {
        foreach($request->pages as $page){
            $remarketing = RemarketingInterval::create([
                "name" => $request->input('name'),
                "facebook_page_id" => $page,
                "start_after" => $request->input('start_after')*$request->input('start_time_unit'),
                "send_after_each" => $request->input('send_after_each')*$request->input('time_unit'),
                "devide_by" => $request->input('devide_by'),
                'template' => $request->input('template'),
                'category' => $request->input('category'),
            ]);
        }
        return back()->with('success', "Remarketing message has been created");
    }
    

    /**
     * activate the specified resource.
     */
    public function activate(RemarketingInterval $remarketing)
    {
        if($remarketing->is_active)
        {
            return redirect()->route('remarketing_interval')->with('success', session('success'))->with('error', session('error'));
        }
        return view('pages.remarketing2.activate')->with('remarketing', $remarketing);
    }

    /**
     * activate the specified resource.
     */
    public function activate_store(RemarketingInterval $remarketing)
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
    public function deactivate_store(RemarketingInterval $remarketing)
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
    public function edit(RemarketingInterval $remarketing)
    {
        $pages = FacebookPage::where('expired_at', null)->where('type', 'business')->get();
        $templates = MessagesTemplates::where('deleted_at', null)->get();
        $categories = RemarketingCategory::where('deleted_at', null)->get();
        return view('pages.remarketing2.edit')->with('remarketing', $remarketing)->with('pages', $pages)->with('templates', $templates)->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRemarketingIntervalRequest $request, RemarketingInterval $remarketing)
    {
        foreach($request->pages as $page){
            if($page == $remarketing->facebook_page_id)
            {
                $remarketing->update([
                    "name" => $request->input('name'),
                    "facebook_page_id" => $page,
                    "start_after" => $request->input('start_after')*$request->input('start_time_unit'),
                    "send_after_each" => $request->input('send_after_each')*$request->input('time_unit'),
                    "devide_by" => $request->input('devide_by'),
                    'template' => $request->input('template'),
                    'category' => $request->input('category'),
                ]);
            }
            else
            {
                $remarketing = RemarketingInterval::create([
                    "name" => $request->input('name'),
                    "facebook_page_id" => $page,
                    "start_after" => $request->input('start_after')*$request->input('start_time_unit'),
                    "send_after_each" => $request->input('send_after_each')*$request->input('time_unit'),
                    "devide_by" => $request->input('devide_by'),
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
    public function destroy(RemarketingInterval $remarketing)
    {
        $remarketing->update(['deleted_at' => now(), 'is_active'=>false]);
        return back()->with('success', "Remarketing message has been deleted");
    }
    public function history(RemarketingInterval $remarketing)
    {
        return view('pages.remarketing2.history')->with('remarketing', $remarketing);
    }
}
