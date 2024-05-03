<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookMessage extends Model
{
    use HasFactory;
    protected $fillable = ['facebook_message_id', 'sented_from', 'message', 'conversation', 'created_at'];
    public function Response_Time()
    {
        $createdAt = Carbon::parse($this->created_at);
        $response = self::where('conversation', $this->conversation)
        ->where('sented_from', '!=', $this->sented_from)
        ->where('created_at', '>', $createdAt)
        ->orderBy('created_at', 'asc')
        ->first();
        if ($response) {
            $createdAt = Carbon::parse($this->created_at); 
            $responseCreatedAt = Carbon::parse($response->created_at);
            return $createdAt->diffInMinutes($responseCreatedAt);
        }
        return 0;
    }
    
}
