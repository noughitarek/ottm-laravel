<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersImport extends Model
{
    use HasFactory;
    protected $fillable = ["name", "phone", "phone2", "commune", "desk", "address", "stopdesk", "fragile", "is_test", "description", "total_price", "delivery_price", "clean_price", "created_by", "IP", "intern_tracking", "from_stock", "products", "uploaded_at"];

    public function Commune()
    {
        return Commune::find($this->commune);
    }
    public function Created_by()
    {
        return User::find($this->created_by);
    }
    public function Desk()
    {
        $desk = Desk::find($this->desk);
        if($desk) return $desk;
        return new Desk(['name' => 'Unassigned']);
    }
}
