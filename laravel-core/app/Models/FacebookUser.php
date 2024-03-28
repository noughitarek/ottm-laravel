<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookUser extends Model
{
    use HasFactory;
    protected $fillable = ['facebook_user_id', 'name', 'email'];

    public function Conversation()
    {
        return FacebookConversation::where('user', $this->facebook_user_id)->first();
    }
    
}
