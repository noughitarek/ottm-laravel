<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Funding;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::whereNull('deleted_at')->get();
        return view('pages.purchases.index')->with('purchases', $purchases);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::whereNull('deleted_at')->get();
        $testsFunding = Funding::whereNull('deleted_at')->where('type', 'tests')->get();
        $productsFunding = Funding::whereNull('deleted_at')->where('type', 'products')->get();
        $desks = Desk::whereNull('deleted_at')->get();
        return view('pages.purchases.create')->with('products', $products)->with('testsFunding', $testsFunding)->with('productsFunding', $productsFunding)->with('desks', $desks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $purchase = Purchase::create([
            'total_amount' => $request->input('total_amount'),
            'products_funding' => $request->input('products_funding'),
            'tests_funding' => $request->input('tests_funding')
        ]);
        foreach($request->products as $product)
        {
            if(isset($product['id']) && $product['id']!="")
            {
                for($i=0; $i<$product['quantity']; $i++)
                {
                    PurchaseProducts::create([
                        'purchase_price' => $product['unit_price'],
                        'purchase' => $purchase->id,
                        'product' => $product['id'],
                        'desk' => $product['desk'],
                        'state' => 'ready'
                    ]);
                }
            }
        }
        return back()->with('success', 'Purchase has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $products = Product::whereNull('deleted_at')->get();
        $testsFunding = Funding::whereNull('deleted_at')->where('type', 'tests')->get();
        $productsFunding = Funding::whereNull('deleted_at')->where('type', 'products')->get();
        $desks = Desk::whereNull('deleted_at')->get();
        return view('pages.purchases.edit')->with('products', $products)->with('testsFunding', $testsFunding)->with('productsFunding', $productsFunding)->with('desks', $desks)->with('purchase', $purchase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->update(["deleted_at" => now()]);
        return back()->with('success', 'Purchase has been deleted successfully');
    }
}
