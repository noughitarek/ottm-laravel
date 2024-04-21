<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponderMessage extends Model
{
    use HasFactory;
    protected $fillable = ['responder', 'facebook_conversation_id', 'last_use', 'template'];
}
