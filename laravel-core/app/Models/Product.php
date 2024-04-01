<?php

namespace App\Models;

use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug', 'deleted_at'];

    public function Stock()
    {
        $total_delivered = $total_delivery = $total_toWilaya = 0;
        foreach(Order::Delivered()->get() as $order){
            $total_delivered += OrderProducts::where('product', $this->id)->where('order', $order->id)->sum('quantity');
        }
        foreach(Order::Delivery()->get() as $order){
            $total_delivery += OrderProducts::where('product', $this->id)->where('order', $order->id)->sum('quantity');
        }
        foreach(Order::ToWilaya()->get() as $order){
            $total_toWilaya += OrderProducts::where('product', $this->id)->where('order', $order->id)->sum('quantity');
        }
        $stock = Stock::where('product', $this->id)->sum('quantity');
        return $stock - $total_toWilaya - $total_delivery - $total_delivered;
    } 
}
