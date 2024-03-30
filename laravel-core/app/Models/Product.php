<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug', 'deleted_at'];

    public function Stock()
    {
        $total_delivered = Order::Delivered()->where('product', $this->id)->sum('quantity');
        $total_delivery = Order::Delivery()->where('product', $this->id)->sum('quantity');
        $total_toWilaya = Order::ToWilaya()->where('product', $this->id)->sum('quantity');
        $stock = Stock::where('product', $this->id)->sum('quantity');
        return $stock - $total_toWilaya - $total_delivery - $total_delivered;
    } 
}
