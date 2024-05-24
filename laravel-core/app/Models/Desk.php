<?php

namespace App\Models;

use App\Models\Wilaya;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Desk extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'ecotrack_link', 'ecotrack_token', 'deleted_at'];

    public function Wilayas()
    {
        return Wilaya::where('desk', $this->id)->get();
    }
    public function Update_API()
    {
        
        $apiUrl = $this->ecotrack_link."api/v1/get/fees";
        $data = array(
            'api_token' => $this->ecotrack_token,
            'url' => base64_encode($apiUrl),
            'typeRequest' => 'get'
        );

        $helperUrl = "https://sigma-helper.000webhostapp.com/".'?' . http_build_query($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $helperUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
        ));

        $result = curl_exec($ch);
        $responseData = json_decode($result, true);

        foreach($responseData["livraison"] as $wilayaData){
            $wilaya = Wilaya::find($wilayaData["wilaya_id"]);
            if($wilaya && $wilaya->desk == $this->id) {
                $wilaya->update([
                    "delivery_price" => $wilayaData["tarif"],
                    "stopdesk" => $wilayaData["tarif_stopdesk"]!=0,
                ]);
            }
        }
        curl_close($ch);
    }
    public function CommunePhone(Commune $commune)
    {
        return DeliveryMen::where('desk', $this->id)->where('commune', $commune->id)->first()->phone_number??null;
    }

    public function Stock(Product $product)
    {
        $initial_stock = Stock::where('product', $product->id)->where('desk', $this->id)->sum('quantity');

        $delivery = 0;
        foreach(Order::Delivery()->get() as $order){
            $orders = OrderProducts::where('product', $this->id)->where('order', $order->id)->get();
            foreach($orders as $delivery_order){
                if($delivery_order->Desk()->id == $this->id)$delivery += $delivery_order->quantity;
            }
        }
        $delivered = 0;
        foreach(Order::Delivered()->get() as $order){
            $orders = OrderProducts::where('product', $this->id)->where('order', $order->id)->get();
            foreach($orders as $delivered_order){
                if($delivered_order->Desk()->id == $this->id)$delivered += $delivered_order->quantity;
            }
        }
        $towilaya = 0;
        foreach(Order::ToWilaya()->get() as $order){
            $orders = OrderProducts::where('product', $this->id)->where('order', $order->id)->get();
            foreach($orders as $towilaya_order){
                if($towilaya_order->Desk()->id == $this->id)$towilaya += $towilaya_order->quantity;
            }
        }
        return $initial_stock - $towilaya - $delivery - $delivered;
    } 
    
}
