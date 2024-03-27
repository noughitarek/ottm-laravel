<?php

namespace App\Models;

use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookConversation extends Model
{
    use HasFactory;
    public function Messages()
    {
        return $this->hasMany(FacebookMessage::class, 'conversation')->latest('created_at');
    }

    public function user()
    {
        return $this->belongsTo(FacebookUser::class, 'user');
    }
}
