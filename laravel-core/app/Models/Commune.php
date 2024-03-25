<?php

namespace App\Models;

use App\Models\Wilaya;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commune extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'wilaya'];
    public function Wilaya()
    {
        $wilaya = Wilaya::where("id", $this->wilaya)->first();
        if($wilaya)return $wilaya;
        return false;
    }

}
