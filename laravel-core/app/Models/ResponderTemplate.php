<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponderTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['order', 'responder', 'template']; 
    public function Template()
    {
        return MessagesTemplates::find($this->template);
    }
}
