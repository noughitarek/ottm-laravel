<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemarketingIntervalTemplates extends Model
{
    use HasFactory;
    protected $fillable = ['remarketing', 'template', 'order', 'used'];
    public function Template()
    {
        return MessagesTemplates::find($this->template);
    }
}
