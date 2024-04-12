<?php

namespace App\Http\Controllers;

use App\Models\RemarketingCategory;
use App\Http\Requests\StoreRemarketingCategoryRequest;
use App\Http\Requests\UpdateRemarketingCategoryRequest;

class RemarketingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = RemarketingCategory::where('deleted_at', null)->where('parent', null)->get();
        $all_categories = RemarketingCategory::where('deleted_at', null)->get();
        return view('pages.remarketingcategories')->with('categories', $categories)->with('all_categories', $all_categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRemarketingCategoryRequest $request)
    {
        RemarketingCategory::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'parent' => $request->input('parent')
        ]);
        return back()->with('success', 'category has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRemarketingCategoryRequest $request, RemarketingCategory $category)
    {
        $category->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'parent' => $request->input('parent')
        ]);
        return back()->with('success', 'category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RemarketingCategory $category)
    {
        $category->update([
            'deleted_at' => now(),
            'slug' => $category->slug."deleted",
            'parent' => null
        ]);
        RemarketingCategory::where('parent', $category->id)
        ->update(['parent' => RemarketingCategory::where('slug', 'undefined')->first()->id]);
        Remarketing::where('category', $category->id)
        ->update(['category' => RemarketingCategory::where('slug', 'sub-undefined')->first()->id]);
        RemarketingInterval::where('category', $category->id)
        ->update(['category' => RemarketingCategory::where('slug', 'sub-undefined')->first()->id]);
        return back()->with('success', 'category has been deleted successfully');
    }
}
