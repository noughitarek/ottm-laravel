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
    public function store(StoreFundingRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Funding $funding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFundingRequest $request, Funding $funding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Funding $funding)
    {
        //
    }
}
