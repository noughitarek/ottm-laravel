<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funding extends Model
{
    use HasFactory;
    protected $fillable = ["name", "total_amount", "type", "investor_pourcentage", "investor", "deleted_at"];
    public function Funder()
    {
        if($this->investor != null)
        {
            return Investor::find($this->investor);
        }
        else
        {
            return new Investor([
                'name' => 'n/a',
            ]);
        }
    }
}
