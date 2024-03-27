<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookMessage extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'sented_from', 'message', 'conversation', 'created_at'];
}
