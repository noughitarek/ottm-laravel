<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicerOrdersProducts extends Model
{
    use HasFactory;
    protected $fillable = ['order', 'product', 'quantity'];
}
