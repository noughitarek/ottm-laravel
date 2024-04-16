<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicerOrders extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'phone2', 'address', 'commune', 'total_price', 'delivery_price', 'clean_price', 'recovered', 'tracking', 'stopdesk', 'facebook_conversation_id'];
    public function Commune()
    {
        return Commune::find($this->commune);
    }

    public function Conversation()
    {
        return FacebookConversation::where('facebook_conversation_id', $this->facebook_conversation_id)->first();
    }

    public function Product()
    {
        return InvoicerOrdersProducts::where('order', $this->id)->get();
    }

    public function Desk()
    {
        $desk = Desk::find($this->desk);
        if($desk) return $desk;
        return new Desk(['name' => 'Unassigned']);
    }
}
