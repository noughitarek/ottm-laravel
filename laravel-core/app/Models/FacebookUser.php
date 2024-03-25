<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookUser extends Model
{
    use HasFactory;
    protected $fillable = ['facebook_user_id', 'name', 'email', 'can_reply'];

    public function Conversation()
    {
        $messages = Message::where('sented_to', $this->facebook_user_id)->orWhere('sented_by', $this->facebook_user_id)->orderBy('created_at', "desc")->get();
        return $messages;
    }
}
