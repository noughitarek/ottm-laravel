<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookUser extends Model
{
    use HasFactory;
    protected $fillable = ['facebook_user_id', 'name', 'email'];
}
