<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class OrdersStatesRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-orders-states';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(!config('settings.scheduler.orders_states_check'))
            exit;
        #Order::Save_orders();
        $orders = Order::orderBy('updated_at', 'asc')->get();
        $ordersToUpdate = [];

        foreach($orders as $order){
            if(($order->State() != "Archived" && 
                $order->State() != "Back ready" &&
                $order->State() != "Recovered" &&
                $order->State() != "Pending") || 
                ($order->State() == "Pending" && $order->tracking != null)
            )
            {
                $ordersToUpdate[] = $order;
            }
        }
        $orders = array_chunk($ordersToUpdate, 96);
        foreach($orders as $page){
            $this->Update_state($page);
        }
    }
    public function Update_state($orders)
    {
        $trackings = [];
        foreach($orders as $order){
            $trackings[] = $order->tracking;
        }
        $data = array(
            "trackings" => implode(',', $trackings),
            "status" => "all",
            'api_token' => $order->Desk()->ecotrack_token
        );
        $apiUrl = $order->Desk()->ecotrack_link."api/v1/get/orders/status";
        $resultData = Order::Send_API_Static($apiUrl, $data, "GET", $order->Desk()->ecotrack_token);

        if(isset($resultData["data"])){
            foreach($resultData["data"] as $tracking=>$status){
                $status = $status["status"];
                $order = Order::where('tracking', $tracking)->first();
                if($order->validated_at == null && ($status == "en_preparation_stock" || $status == "vers_hub"))
                {
                    $order->validated_at = now();
                    $order->After_Validating();
                }
                elseif($order->shipped_at == null && ($status == "en_hub"))
                {
                    $order->shipped_at = now();
                    $order->After_Shipping();
                }
                elseif($order->wilaya_at == null && ($status == "vers_wilaya"))
                {
                    $order->wilaya_at = now();
                    $order->After_Wilaya();
                }
                elseif($order->delivery_at == null && ($status == "en_livraison"))
                {
                    $order->delivery_at = now();
                    $order->After_Delivery();
                }
                elseif($order->delivered_at == null && ($status == "livre_non_encaisse"))
                {
                    $order->delivered_at = now();
                    $order->After_Delivered();
                }
                elseif($order->ready_at == null && ($status == "encaisse_non_paye" || $status == "paiements_prets"))
                {
                    $order->ready_at = now();
                    $order->After_Ready();
                }
                elseif($order->recovered_at == null && ($status == "paye_et_archive"))
                {
                    $order->recovered_at = now();
                    $order->After_Recovering();
                }
                elseif($order->back_at == null && ($status == "suspendu" || $status == "retour_chez_livreur" || $status == "retour_transit_entrepot"))
                {
                    $order->back_at = now();
                    $order->After_Back();
                }
                elseif($order->back_ready_at == null && ($status == "retour_en_traitement" || $status == "retour_recu" || $status == "retour_archive"))
                {
                    $order->back_ready_at = now();
                    $order->After_Back_Ready();
                }
                $order->save();
            }
        }else{
            return false;
        }

    }
}

