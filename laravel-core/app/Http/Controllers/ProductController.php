<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::wherenull('deleted_at')->get();
        $desks = Desk::wherenull('deleted_at')->get();
        return view('pages.products')->with('products', $products)->with('desks', $desks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Product::create([
            'name' => $request->input('name'),
            'slug'  => $request->input('slug')
        ]);
        return back()->with("success", "product has been created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->input('name'),
            'slug'  => $request->input('slug')
        ]);
        return back()->with("success", "product has been updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->update(
        [
            'slug' => null,
            'deleted_at' => now()
        ]);
        return back()->with("success", "product has been deleted successfully");
    }
}
