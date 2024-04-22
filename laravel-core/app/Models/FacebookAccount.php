<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookAccount extends Model
{
    use HasFactory;
    protected $fillable = ['account_id', 'name', 'username', 'email_pwd', 'pwd', 'marketplace_at', 'deleted_at'];
}
