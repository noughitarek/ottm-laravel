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
        $categories = FacebookCategories::where('deleted_at', null)->orderBy('created_at', 'desc')->paginate(20)->oneachside(2);
        $all_categories = FacebookCategories::where('deleted_at', null)->orderBy('created_at', 'desc')->get();
        return view('pages.facebook_categories')->with('categories', $categories)->with('all_categories', $all_categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function category(FacebookCategories $category)
    {
        $all_categories = FacebookCategories::where('deleted_at', null)->orderBy('created_at', 'desc')->get();
        $accounts = FacebookAccount::where('deleted_at', null)->where('category', $category->id)->orderBy('created_at', 'desc')->paginate(20)->oneachside(2);
        return view('pages.accounts')->with('all_categories', $all_categories)->with('accounts', $accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacebookAccountRequest $request)
    {
        $account = FacebookAccount::create([
            'account_id' => $request->input('id'),        
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'username' => $request->input('username'),
            'pwd' => $request->input('pwd'),
            'email_pwd' => $request->input('email_pwd')
        ]);
        return back()->with('success', 'account has been created successfully');

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
     * Update the specified resource in storage.
     */
    public function update(UpdateFacebookAccountRequest $request, FacebookAccount $account)
    {
        $account->update([
            'account_id' => $request->input('id'),        
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'username' => $request->input('username'),
            'pwd' => $request->input('pwd'),
            'email_pwd' => $request->input('email_pwd')
        ]);
        return back()->with('success', 'account has been edited successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_category(Request $request, FacebookCategories $category)
    {
        $category->update([
            'name' => $request->input('name')
        ]);
        return back()->with('success', 'category has been deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacebookAccount $account)
    {
        $account->update([
            'deleted_at' => now()
        ]);
        return back()->with('success', 'account has been deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_category(FacebookCategories $category)
    {
        $accounts = FacebookAccount::where('deleted_at', null)->where('category', $category->id)->update(['category'=>null]);
        $category->update([
            'deleted_at' => now()
        ]);
        return back()->with('success', 'category has been deleted successfully');
    }
}
