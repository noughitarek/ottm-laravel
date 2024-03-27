<?php

namespace App\Models;

use App\Models\FacebookPage;
use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookConversation extends Model
{
    use HasFactory;
    
    protected $fillable = ['id', 'page', 'user', 'can_reply'];

    public function Messages()
    {
        return $this->hasMany(FacebookMessage::class, 'conversation')->latest('created_at');
    }

    public function User()
    {
        $user = FacebookUser::find($this->user);
        return $user;
    }

    public function Page()
    {
        $page = FacebookPage::find($this->page);
        return $page;
    }
}
