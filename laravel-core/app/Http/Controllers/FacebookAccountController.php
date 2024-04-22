<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacebookAccount;
use App\Models\FacebookCategories;
use App\Http\Requests\StoreFacebookAccountRequest;
use App\Http\Requests\UpdateFacebookAccountRequest;

class FacebookAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = FacebookCategories::where('deleted_at', null)->paginate(20)->oneachside(2);
        return view('pages.facebook_categories')->with('accounts', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacebookAccountRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_category(Request $request)
    {
        FacebookCategories::create([
            'name' => $request->input('name')
        ]);
        return back()->with('success', 'category has been created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(FacebookAccount $facebookAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FacebookAccount $facebookAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacebookAccountRequest $request, FacebookAccount $facebookAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacebookAccount $facebookAccount)
    {
        //
    }
}
