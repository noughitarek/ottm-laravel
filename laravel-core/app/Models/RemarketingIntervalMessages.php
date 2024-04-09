<?php

namespace App\Models;

use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RemarketingIntervalMessages extends Model
{
    use HasFactory;
    protected $fillable = ['remarketing', 'facebook_conversation_id', 'last_use'];
    
    public function Conversation()
    {
        return FacebookConversation::where('facebook_conversation_id', $this->facebook_conversation_id)->first();
    }
    public function Page()
    {
        return $this->Conversation()->Page();
    }
}
