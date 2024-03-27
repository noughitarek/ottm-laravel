<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    use HasFactory;
    protected $fillable = ['name_ar', 'delivery_price', 'desk'];
    public function Desk()
    {
        if($this->desk != null)
        {
            return Desk::find($this->desk);
        }
        else
        {
            return new Desk(['name' => 'Unassigned']);
        }
    }
}
