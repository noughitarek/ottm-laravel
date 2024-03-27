<?php

namespace App\Models;

use App\Models\Wilaya;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commune extends Model
{
    use HasFactory;
    
    public function Wilaya()
    {
        return Wilaya::find($this->wilaya);
    }
}
