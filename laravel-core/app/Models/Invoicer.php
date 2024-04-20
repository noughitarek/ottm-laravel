<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoicer extends Model
{
    use HasFactory;
    protected $fillable = ['total_amount', 'total_orders', 'desk'];
    public function Products()
    {
        $products = InvoicerProducts::join('invoicer_orders_products as IOP', 'IOP.product', '=', 'invoicer_products.id')
        ->join('invoicer_orders as IO', 'IO.id', '=', 'IOP.order')
        ->where('IO.invoice', $this->id)
        ->groupBy('invoicer_products.id')
        ->select('invoicer_products.*',
        DB::raw('SUM(IOP.quantity) as total_quantity'),
        DB::raw('SUM(IOP.quantity)*invoicer_products.purchase_price as capital'),
        DB::raw('SUM(IOP.quantity)*invoicer_products.min_price as clean'),
        DB::raw('SUM(IOP.quantity)*(invoicer_products.min_price-invoicer_products.purchase_price) as benefits'),
        DB::raw('SUM(IO.delivery_extra) as delivery_extra'),
        DB::raw('SUM(IO.desk_extra) as desk_extra')
        
        )
        ->get();
        return $products;
    }
    public function Desk()
    {
        return Desk::find($this->desk)?? new Desk(['name'=>'n/a']);
    }
    public function Total_orders()
    {
        return InvoicerOrders::where('invoice', $this->id)->count();
    }
    public function Total_capital()
    {
        return $this->Products()->sum('capital');
    }
    public function Total_clean()
    {
        return $this->Products()->sum('clean');
    }
    public function Total_benefits()
    {
        return $this->Products()->sum('benefits');
    }
    public function Total_delivery_extra()
    {
        return $this->Products()->sum('delivery_extra');
    }
    public function Total_desk_extra()
    {
        return $this->Products()->sum('desk_extra');
    }
    public function Orders()
    {
        return InvoicerOrders::where('invoice', $this->id)->get();
    }
    public function Total()
    {
        return $this->Orders()->sum('total_price');
    }
    public function Delivery()
    {
        return $this->Orders()->sum('delivery_price');
    }
    public function Clean()
    {
        return $this->Orders()->sum('clean_price');
    }
}
