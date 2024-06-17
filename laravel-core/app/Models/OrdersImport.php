<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrdersImport extends Model
{
    use HasFactory;
    protected $fillable = ["name", "phone", "phone2", "commune", "desk", "address", "stopdesk", "fragile", "is_test", "description", "total_price", "delivery_price", "clean_price", "created_by", "IP", "intern_tracking", "from_stock", "products", "uploaded_at"];

    public function Commune()
    {
        return Commune::find($this->commune);
    }
    public function Created_by()
    {
        return User::find($this->created_by);
    }
    public function Desk()
    {
        $desk = Desk::find($this->desk);
        if($desk) return $desk;
        return new Desk(['name' => 'Unassigned']);
    }
    public function Have_products_problem()
    {
        $charactersToRemove = array("-", "/", "\\");
        $products = [];
        foreach(explode('+', $this->products) as $product)
        {
            $product = trim(str_replace($charactersToRemove, "", $product));
            $productQuantity = 1;
            $productRow = null;
            foreach(config('settings.quantities') as $quantity=>$label)
            {
                if($label != null)
                {
                    if(strpos($product, $label) === 0)
                    {
                        $productQuantity = $quantity;
                        $productsName = trim(explode($label, $product)[1]);
                        $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
                        return true;
                    }
                }
            }
            if(!$productRow)
            {
                $productsName = $product;
                $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
            }
            if(!$productRow)return true;
            return false;
        }
        return $products;
    }
    public function Products()
    {
        $charactersToRemove = array("-", "/", "\\");
        $products = [];
        foreach(explode('+', $this->products) as $product)
        {
            $product = trim(str_replace($charactersToRemove, "", $product));
            $productQuantity = 1;
            $productRow = null;
            foreach(config('settings.quantities') as $quantity=>$label)
            {
                if($label != null)
                {
                    if(strpos($product, $label) === 0)
                    {
                        $productQuantity = $quantity;
                        $productsName = trim(explode($label, $product)[1]);
                        $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
                        break;
                    }
                }
            }
            if(!$productRow)
            {
                $productsName = $product;
                $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
            }
            $sure = true;
            if(!$productRow) $sure = null;
            $products[] = ['qte'=>$productQuantity ,"name"=>$productsName, "sure"=>$sure];
        }
        return $products;
    }
}
