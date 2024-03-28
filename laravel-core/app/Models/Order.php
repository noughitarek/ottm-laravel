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
            "tracking", "intern_tracking", "IP", "fragile", "stopdesk", "product", "conversation", "desk", "description",
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
        $data = array(
            'referece' => $this->intern_tracking,
            'nom_client' => $this->name,
            'telephone' => preg_replace("/[^0-9]/", "", $this->phone),
            'telephone_2' => preg_replace("/[^0-9]/", "", $this->phone2),
            'adresse' => $this->address,
            'fragile' => $this->fragile,
            'quantity' => $this->quantity,
            'code_wilaya' => $this->Commune()->Wilaya()->id,
            'commune' =>  $this->Commune()->name,
            'stop_desk' => $this->stopdesk,
            'montant' => $this->total_price,
            'remarque' => $this->description,
            'produit' => $this->Product()->slug,
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
    
    public function Validate_Ecotrack()
    {
        return false;
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

        $helperUrl = "https://sigma-helper.000webhostapp.com/".'?' . http_build_query(array_merge($data, $data0));

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

        $helperUrl = "https://sigma-helper.000webhostapp.com/".'?' . http_build_query(array_merge($data, $data0));
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
        if(config('settings.messages_template.validating') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.validating'));
    }
    public function After_Shipping()
    {
        if(config('settings.messages_template.shipping') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.shipping'));
    }
    public function After_Wilaya()
    {
        if(config('settings.messages_template.wilaya') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.wilaya'));
    }
    public function After_Delivery()
    {
        if(config('settings.messages_template.delivery') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.delivery'));
    }
    public function After_Delivered()
    {
        if(config('settings.messages_template.delivered') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.delivered'));
    }
    public function After_Ready()
    {
        if(config('settings.messages_template.ready') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.ready'));
    }
    public function After_Recovering()
    {
        if(config('settings.messages_template.recovering') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.recovering'));
    }
    public function After_Back()
    {
        if(config('settings.messages_template.back') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.back'));
    }
    public function After_Back_Ready()
    {
        if(config('settings.messages_template.back_Ready') == ''){
            return false;
        }
        return $this->Conversation()->Send_Message(config('settings.messages_template.back_Ready'));
    }
    
}
