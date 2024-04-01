<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;
    protected $fillable = ['order', 'product', 'quantity'];
    public function Product()
    {
        return Product::find($this->product);
    }
}
