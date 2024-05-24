<?php

namespace App\Models;

use App\Models\Desk;
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
        $stock = 0;
        $desks = Desk::wherenull('deleted_at')->get();
        foreach($desks as $desk)
        {
            $stock += $desk->Stock($this);
        }
        return $stock;
    } 
}
