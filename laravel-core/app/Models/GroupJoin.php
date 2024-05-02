<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupJoin extends Model
{
    use HasFactory;
    public function Joiner()
    {
        return GroupJoiner::find($this->joiner);
    }
    public function Group()
    {
        return FacebookGroup::where('facebook_group_id', $this->facebook_group_id)->first();
    }
}
