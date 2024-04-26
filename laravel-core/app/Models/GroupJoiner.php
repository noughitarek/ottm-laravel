<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupJoiner extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'keywords', 'category', 'max_join', 'join', 'each', 'start_at', 'end_at', 'deleted_at'];
    public function Categories()
    {
        return FacebookCategories::whereIn('id', function($query) {
            $query->select('category')
                  ->from('group_joiners_categories')
                  ->where('group_joiner', $this->id);
        })->get();
    }
    public function In_Category($category)
    {
        return GroupJoinersCategory::where('group_joiner', $this->id)->where('category', $category)->first()!==null;
    }
}
