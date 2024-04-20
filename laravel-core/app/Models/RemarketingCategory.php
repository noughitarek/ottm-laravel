<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemarketingCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent', 'deleted_at'];
    public function Sub_Categories()
    {
        return RemarketingCategory::where('deleted_at')->where('parent', $this->id)->get();
    }
    public function Remarketings()
    {
        return Remarketing::where('category', $this->id)->where('deleted_at', null)->get();
    }
    public function Remarketings_Interval()
    {
        return RemarketingInterval::where('category', $this->id)->where('deleted_at', null)->get();
    }
}
