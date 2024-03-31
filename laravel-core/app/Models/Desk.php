<?php

namespace App\Models;

use App\Models\Wilaya;
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
    public static function Update_API()
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
                    "real_price" => $wilayaData["tarif"],
                    "shown_price" => $wilayaData["tarif"]-300,
                ]);
            }
        }
        curl_close($ch);
    }

    
}
