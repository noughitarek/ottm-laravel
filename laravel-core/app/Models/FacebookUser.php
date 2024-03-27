<?php

namespace App\Models;

use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookUser extends Model
{
    use HasFactory;
    
    public function Conversations()
    {
        return $this->hasMany(FacebookConversation::class, 'user');
    }
}
