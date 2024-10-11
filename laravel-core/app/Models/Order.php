<?php

namespace App\Models;

use App\Models\Commune;
use App\Models\Product;
use App\Models\OrderProducts;
use App\Models\FacebookConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["name", "phone", "phone2", "address", "commune", "quantity", "total_price", "delivery_price", "clean_price",
            "tracking", "intern_tracking", "IP", "fragile", "stopdesk", "product", "conversation", "desk", "description", "from_stock",
            "validated_at", "shipped_at", "wilaya_at", "recovered_at", "delivery_at", "delivered_at", "ready_at", "back_at", "back_ready_at", "archived_at", "created_by"];

    
    public function Commune()
    {
        return Commune::find($this->commune);
    }

    public function Conversation()
    {
        return FacebookConversation::where('facebook_conversation_id', $this->conversation)->first();
    }

    public function Product()
    {
        return OrderProducts::where('order', $this->id)->get();
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
        $reference = "";
        foreach($this->Product() as $i=>$product)
        {
            $reference .= ($i!=0?" + ":"").config('settings.quantities')[$product->quantity].'/'.$product->Product()->name;
        }
        $data = array(
            'reference' => $reference,
            'nom_client' => $this->name,
            'telephone' => preg_replace("/[^0-9]/", "", $this->phone),
            'telephone_2' => preg_replace("/[^0-9]/", "", $this->phone2),
            'adresse' => $this->address,
            'fragile' => $this->fragile,
            'code_wilaya' => $this->Commune()->Wilaya()->id,
            'commune' =>  $this->Commune()->name,
            'stop_desk' => $this->stopdesk,
            'montant' => $this->total_price,
            'remarque' => $this->description,
            'type' => 1,
            'api_token' => $this->Desk()->ecotrack_token
        );
        $apiUrl = $this->Desk()->ecotrack_link."api/v1/create/order";
        $resultData = self::Send_API($apiUrl, $data, "POST");
        try
        {
            if ($resultData && isset($resultData['tracking']))
            {
                $this->tracking = $resultData['tracking'];
                $this->save();
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $exception)
        {
            return false;
        }
    }
    public function Add_To_Ecotrack_Stock()
    {
        $reference = "";
        foreach($this->Product() as $i=>$product)
        {
            if($i!=0)
            {
                $products .= ','.$product->Product()->slug;
                $quantites .= ','.$product->quantity;

            }
            else
            {
                $products = $product->Product()->slug;
                $quantites = $product->quantity;
            }
            $reference .= ($i!=0?" + ":"").config('settings.quantities')[$product->quantity].'/'.$product->Product()->name;
        }
        $data = array(
            'reference' => $reference,
            'nom_client' => $this->name,
            'telephone' => preg_replace("/[^0-9]/", "", $this->phone),
            'telephone_2' => preg_replace("/[^0-9]/", "", $this->phone2),
            'adresse' => $this->address,
            'produit' => $products,
            'quantite' => $quantites,
            'fragile' => $this->fragile,
            'code_wilaya' => $this->Commune()->Wilaya()->id,
            'commune' =>  $this->Commune()->name,
            'stop_desk' => $this->stopdesk,
            'montant' => $this->total_price,
            'remarque' => $this->description,
            'type' => 1,
            'stock' => 1,
            'api_token' => $this->Desk()->ecotrack_token
        );
        $apiUrl = $this->Desk()->ecotrack_link."api/v1/create/order";
        $resultData = self::Send_API($apiUrl, $data, "POST");
        try
        {
            if ($resultData && isset($resultData['tracking']))
            {
                $this->tracking = $resultData['tracking'];
                $this->save();
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $exception)
        {
            return false;
        }
    }
    
    public function Validate_Ecotrack()
    {
        $data = array(
            "tracking" => $this->tracking,
            'api_token' => $this->Desk()->ecotrack_token
        );
        $apiUrl = $this->Desk()->ecotrack_link."api/v1/valid/order";
        $resultData = self::Send_API($apiUrl, $data, "POST");
        try
        {
            if ($resultData && isset($resultData['success']) && $resultData['success'])
            {
                $this->validated_at = now();
                $this->save();
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $exception)
        {
            return false;
        }
    }

    public function Send_API($url, $data, $type="POST")
    {
        $data0 = array(
            'api_token' => $this->Desk()->ecotrack_token,
            'url' => base64_encode($url),
            'typeRequest' => $type=="POST"?'post':'get'
        );

        $helperUrl = "http://www.sigma-helper.rf.gd/".'?' . http_build_query(array_merge($data, $data0));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $helperUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
        ));
        $result = curl_exec($ch);
        
        if (curl_errno($ch))
        {
            echo 'Error: Can\'t send api request\n';
        }
        elseif($result)
        {
            $resultData = json_decode($result, true);
            curl_close($ch);
            if(isset($resultData['message']) && $resultData['message'] == "Too Many Attempts.")
            {
                echo 'Error: Too Many Attempts\n';
            }
            else
            {
                return $resultData;
            }
        }
        else
        {
            echo 'Error: Can\'t send api request 2\n';
        }

    }

    public static function Send_API_Static($url, $data, $type="POST", $token)
    {
        $data0 = array(
            'api_token' => $token,
            'url' => base64_encode($url),
            'typeRequest' => $type=="POST"?'post':'get'
        );

        $helperUrl = "http://www.sigma-helper.rf.gd/".'?' . http_build_query(array_merge($data, $data0));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $helperUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
        ));
        $result = curl_exec($ch);
        
        if (curl_errno($ch))
        {
            echo 'Error: Can\'t send api request\n';
        }
        elseif($result)
        {
            $resultData = json_decode($result, true);
            curl_close($ch);
            if(isset($resultData['message']) && $resultData['message'] == "Too Many Attempts.")
            {
                echo 'Error: Too Many Attempts\n';
            }
            else
            {
                return $resultData;
            }
        }
        else
        {
            echo 'Error: Can\'t send api request 2\n';
        }

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
    public function After_Validating()
    {
        if($this->validated_at == null)
        {
            $this->validated_at = now();
            $this->save();

            if(config('settings.messages_template.validating') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.validating'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Shipping()
    {
        $this->After_Validating();

        if($this->shipped_at == null)
        {
            $this->shipped_at = now();
            $this->save();

            if(config('settings.messages_template.shipping') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.shipping'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Wilaya()
    {
        $this->After_Shipping();
        if($this->wilaya_at == null)
        {
            $this->wilaya_at = now();
            $this->save();

            if(config('settings.messages_template.wilaya') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.wilaya'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Delivery()
    {
        $this->After_Wilaya();
        if($this->delivery_at == null)
        {
            $this->delivery_at = now();
            $this->save();

            if(config('settings.messages_template.delivery') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.delivery'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Delivered()
    {
        $this->After_Delivery();
        if($this->delivered_at == null)
        {
            $this->delivered_at = now();
            $this->save();
            if(config('settings.messages_template.delivered') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "s";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.delivered'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Ready()
    {
        $this->After_Delivered();
        if($this->ready_at == null)
        {
            $this->ready_at = now();
            $this->save();

            if(config('settings.messages_template.ready') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.ready'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Recovering()
    {
        $this->After_Ready();
        if($this->recovered_at == null)
        {
            $this->recovered_at = now();
            $this->save();

            if(config('settings.messages_template.recovering') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.recovering'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Back()
    {
        $this->After_Delivery();
        if($this->back_at == null)
        {
            $this->back_at = now();
            $this->save();

            if(config('settings.messages_template.back') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first();
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.back'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    public function After_Back_Ready()
    {
        $this->After_Back();
        if($this->back_ready_at == null)
        {
            $this->back_ready_at = now();
            $this->save();

            if(config('settings.messages_template.back_Ready') == ''){
                return false;
            }
            $phone = DeliveryMen::where('commune', $this->commune)->where('desk', $this->desk)->first()->phone_number;
            if(!$phone)
            {
                $phone = "";
            }
            else
            {
                $phone = $phone->phone_number;
            }
            $message = str_replace("{{phone}}", $phone, config('settings.messages_template.back_Ready'));
            if($this->conversation != null)
            {
                return $this->Conversation()->Send_Message($message);
            }
        }
    }
    
}
