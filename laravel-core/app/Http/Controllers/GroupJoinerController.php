<?php

namespace App\Http\Controllers;

use App\Models\GroupJoiner;
use App\Models\FacebookCategories;
use App\Models\GroupJoinersCategory;
use App\Http\Requests\StoreGroupJoinerRequest;
use App\Http\Requests\UpdateGroupJoinerRequest;

class GroupJoinerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group_joiners = GroupJoiner::where('deleted_at', null)->paginate(20)->oneachside(2);
        return view('pages.groupjoiner.index')->with('group_joiners', $group_joiners);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FacebookCategories::where('deleted_at', null)->orderBy('created_at', 'desc')->get();
        return view('pages.groupjoiner.create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupJoinerRequest $request)
    {
        $joiner = GroupJoiner::create([
            'name' => $request->input('name'),
            'keywords' => $request->input('keywords'),
            'max_join' => $request->input('max_join'),
            'join' => $request->input('join'),
            'each' => $request->input('each')*$request->input('time_unit'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at')
        ]);
        foreach($request->input('category') as $category)
        {
            GroupJoinersCategory::create([
                'category' => $category,
                'group_joiner' => $joiner->id
            ]);
        }
        return back()->with('success', 'joiner has been created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupJoiner $joiner)
    {
        $categories = FacebookCategories::where('deleted_at', null)->orderBy('created_at', 'desc')->get();
        return view('pages.groupjoiner.edit')->with('categories', $categories)->with('joiner', $joiner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupJoinerRequest $request, GroupJoiner $joiner)
    {
        $joiner->update([
            'name' => $request->input('name'),
            'keywords' => $request->input('keywords'),
            'max_join' => $request->input('max_join'),
            'join' => $request->input('join'),
            'each' => $request->input('each')*$request->input('time_unit'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at')
        ]);
        GroupJoinersCategory::where('group_joiner', $joiner->id)->delete();
        foreach($request->input('category') as $category)
        {
            GroupJoinersCategory::create([
                'category' => $category,
                'group_joiner' => $joiner->id
            ]);
        }
        return back()->with('success', 'joiner has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupJoiner $joiner)
    {
        $joiner->update(['deleted_at'=>now()]);
        return back()->with('success', 'joiner has been deleted successfully');
    }
}
