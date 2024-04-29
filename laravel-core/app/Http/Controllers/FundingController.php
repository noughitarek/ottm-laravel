<?php

namespace App\Http\Controllers;

use App\Models\Funding;
use App\Models\Product;
use App\Models\Investor;
use App\Http\Requests\StoreFundingRequest;
use App\Http\Requests\UpdateFundingRequest;

class FundingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Investor $investor)
    {
        return view('pages.investors.fundings.index')->with('investor', $investor);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Investor $investor)
    {
        $products = Product::whereNull('deleted_at')->get();
        return view('pages.investors.fundings.create')->with('investor', $investor)->with('products', $products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFundingRequest $request, Investor $investor)
    {
        $products = Product::whereNull('deleted_at')->get();
        Funding::create([
            "name" => $request->input('name'),
            "total_amount" => $request->input('total_amount'),
            "type" => $request->input('type'),
            "investor_pourcentage" => $request->input('investor_pourcentage'),
            "investor" => $investor->id
        ]);
        return back()->with('success', 'Funding has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investor $investor, Funding $funding)
    {
        $products = Product::whereNull('deleted_at')->get();
        return view('pages.investors.fundings.edit')->with('investor', $investor)->with('funding', $funding)->with('products', $products);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFundingRequest $request, Investor $investor, Funding $funding)
    {
        $funding->update([
            "name" => $request->input('name'),
            "total_amount" => $request->input('total_amount'),
            "type" => $request->input('type'),
            "investor_pourcentage" => $request->input('investor_pourcentage'),
            "investor" => $investor->id
        ]);
        return back()->with('success', 'Funding has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investor $investor, Funding $funding)
    {
        $funding->update([
            "deleted_at" => now(),
        ]);
        return back()->with('success', 'Funding has been deleted successfully');
    }
}
