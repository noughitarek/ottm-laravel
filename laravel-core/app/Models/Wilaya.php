<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'real_price', 'shown_price'];

    
    public function Communes()
    {
        $communes = Commune::where("wilaya", $this->id)->get();
        if($communes)return $communes;
        return false;
    }
    public static function Update_API()
    {
        
        $apiUrl = config("settings.ecotrack_link")."api/v1/get/fees";
        $data = array(
            'api_token' => config("settings.ecotrack_api"),
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
            if($wilaya) {
                $wilaya->update([
                    "real_price" => $wilayaData["tarif"],
                    "shown_price" => $wilayaData["tarif"]-300,
                ]);
            }
        }
        curl_close($ch);
    }

    public static function Update_API0()
    {
        
        $data = array(
            'api_token' => config("settings.ecotrack_api")
        );
        $apiUrl = config("settings.ecotrack_link")."api/v1/get/fees";
        $apiUrl .= '?' . http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . config("settings.ecotrack_api"),
            'Content-Type: application/x-www-form-urlencoded',
        ));

        $result = curl_exec($ch);
        $responseData = json_decode($result, true);

        foreach($responseData["livraison"] as $wilayaData){
            $wilaya = Wilaya::find($wilayaData["wilaya_id"]);
            if($wilaya) {
                $wilaya->update([
                    "real_price" => $wilayaData["tarif"],
                    "shown_price" => $wilayaData["tarif"]-300,
                ]);
            }
        }
        curl_close($ch);
    }
}
