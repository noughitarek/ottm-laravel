<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemarketingMessages extends Model
{
    use HasFactory;
    protected $fillable = ['remarketing', 'facebook_conversation_id', 'last_use', 'expire_at'];
    
    public function Conversation()
    {
        return FacebookConversation::where('facebook_conversation_id', $this->facebook_conversation_id)->first();
    }
    public function Page()
    {
        return $this->Conversation()->Page();
    }
    
}
