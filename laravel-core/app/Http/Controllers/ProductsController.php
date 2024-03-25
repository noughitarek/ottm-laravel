<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10)->onEachSide(2);
        return view('pages.products.index')->with('products', $products);
    }
}
