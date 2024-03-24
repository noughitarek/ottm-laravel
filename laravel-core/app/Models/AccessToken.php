<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessToken extends Model
{
    use HasFactory;
    protected $fillable = ["content", "type"];
    public static function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
}
