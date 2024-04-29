<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProducts extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_price', 'purchase', 'product', 'desk', 'state'];
}
