<?php

namespace App\Models;

use App\Models\Commune;
use App\Models\Product;
use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["name", "phone", "phone2", "address", "commune", "quantity", "total_price", "delivery_price", "clean_price",
            "tracking", "intern_tracking", "IP", "fragile", "stopdesk", "product", "conversation", "desk", 
            "validated_at", "shipped_at", "wilaya_at", "recovered_at", "delivery_at", "delivered_at", "ready_at", "back_at", "back_ready_at", "archived_at", "created_by"];

    
    public function Commune()
    {
        return Commune::find($this->commune);
    }

    public function Conversation()
    {
        return FacebookConversation::find($this->conversation);
    }

    public function Product()
    {
        return Product::find($this->product);
    }

    public function Desk()
    {
        $desk = Desk::find($this->desk);
        if($desk) return $desk;
        return new Desk(['name' => 'Unassigned']);
    }

    public function Created_by()
    {
        return User::find($this->created_by);
    }
    
    public function Add_To_Ecotrack()
    {
    }
    
    public function Validate_Ecotrack()
    {
    }

    public function State()
    {
        if($this->archived_at != null) { return 'Archived'; }
        if($this->back_ready_at != null) { return 'Back ready'; }
        if($this->back_at != null) { return 'Back'; }
        if($this->recovered_at != null) { return 'Recovered'; }
        if($this->ready_at != null) { return 'Ready'; }
        if($this->delivered_at != null) { return 'Delivered'; }
        if($this->delivery_at != null) { return 'Delivery'; }
        if($this->wilaya_at != null) { return 'To wilaya'; }
        if($this->shipped_at != null) { return 'Shipped'; }
        if($this->validated_at != null) { return 'Validated'; }
        return 'Pending';
    }
    public static function Pending()
    {
        return Order::where("wilaya_at", null)
        ->where("delivery_at", null)
        ->where("delivered_at", null)
        ->where("ready_at", null)
        ->where("recovered_at", null)
        ->where("back_at", null)
        ->where("back_Ready_at", null)
        ->where("archived_at", null)
        ->orderBy('created_at', 'desc');
    }
    public static function ToWilaya()
    {
        return Order::where("wilaya_at", "!=",null)
        ->where("delivery_at", null)
        ->where("delivered_at", null)
        ->where("ready_at", null)
        ->where("recovered_at", null)
        ->where("back_at", null)
        ->where("back_Ready_at", null)
        ->where("archived_at", null);
    }
    public static function Delivery()
    {
        return Order::where("delivery_at", "!=", null)
        ->where("delivered_at", null)
        ->where("ready_at", null)
        ->where("recovered_at", null)
        ->where("back_at", null)
        ->where("back_Ready_at", null)
        ->where("archived_at", null);
    }
    public static function Delivered()
    {
        return Order::where(function ($query) {
            $query->where(function ($subquery) {
                $subquery->where('delivered_at', '<>', null)
                    ->orWhere('ready_at', '<>', null)
                    ->orWhere('recovered_at', '<>', null);
            })
            ->where('back_at', null)
            ->where('back_ready_at', null)
            ->where('archived_at', null);
        })
        ->orderBy('created_at', 'desc');
    }
    public static function Back()
    {
        return Order::where(function ($query) {
            $query->where(function ($subquery) {
                $subquery->where('back_at', '<>', null)
                         ->orWhere('back_ready_at', '<>', null);
            })
            ->where('archived_at', null);
        })
        ->orderBy('created_at', 'desc');
    }
    public static function Archived()
    {
        return Order::where(function ($query) {
            $query->where('archived_at', '<>', null);
        })
        ->orderBy('created_at', 'desc');
    }
}
