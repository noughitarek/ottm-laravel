<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Stock;
use App\Models\Product;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('deleted_at', null)->get();
        $stock = Stock::where('deleted_at', null)->get();
        $desks = Desk::where('deleted_at', null)->get();
        return view('pages.stock')->with('stock', $stock)->with('products', $products)->with('desks', $desks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        Stock::create([
            'product' => $request->input('product'),
            'desk' => $request->input('desk'),
            'total_amount' => $request->input('total_amount'),
            'quantity' => $request->input('quantity'),
        ]);
        return back()->with('success','Stock has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update([
            'product' => $request->input('product'),
            'desk' => $request->input('desk'),
            'total_amount' => $request->input('total_amount'),
            'quantity' => $request->input('quantity'),
        ]);
        return back()->with('success','Stock has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $stock->update(['deleted_at' => now()]);
        return back()->with('success','Stock has been deleted successfully');
    }
}
