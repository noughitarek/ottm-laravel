<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMen extends Model
{
    use HasFactory;
    protected $fillable = ['desk', 'phone_number', 'commune'];
}
