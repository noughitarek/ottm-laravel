<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desk extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'ecotrack_link', 'ecotrack_token', 'deleted_at'];
}
