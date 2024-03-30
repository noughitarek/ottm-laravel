<?php

namespace App\Models;

use App\Models\Desk;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = ['total_amount', 'quantity', 'desk', 'product', 'deleted_at'];

    public function Desk()
    {
        return Desk::find($this->desk);
    }
    public function Product()
    {
        return Product::find($this->product);
    }
}
