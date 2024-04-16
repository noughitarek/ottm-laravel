<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicerProducts extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'min_price', 'max_price', 'quantity_prices', 'purchase_price', 'deleted_at'];

    public function Quantity_Prices()
    {
        return json_decode($this->quantity_prices, true);
    }
}
