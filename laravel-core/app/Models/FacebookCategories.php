<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookCategories extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'deleted_at'];
}
