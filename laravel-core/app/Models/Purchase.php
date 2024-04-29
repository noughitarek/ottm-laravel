<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['total_amount', 'products_funding', 'tests_funding', 'deleted_at'];
    public function Products_funding()
    {
        if($this->products_funding != null)
        {
            return Funding::find($this->products_funding);
        }
        else
        {
            return new Funding([
                'name' => 'n/a'
            ]);
        }
    }
    public function Tests_funding()
    {
        if($this->tests_funding != null)
        {
            return Funding::find($this->tests_funding);
        }
        else
        {
            return new Funding([
                'name' => 'n/a'
            ]);
        }
    }
    public function Products()
    {
        $products = Product::whereIN('id', function($query){
            $query->select('product')
            ->from('purchase_products')
            ->where('purchase', $this->id);
        })->get();
        return $products;
    }
    public function Product_Quantity(Product $product)
    {
        return PurchaseProducts::where('purchase', $this->id)->where('product', $product->id)->count();
    }
    public function Product_Buy_Price(Product $product)
    {
        return PurchaseProducts::where('purchase', $this->id)->where('product', $product->id)->sum('purchase_price');
    }
    
}
