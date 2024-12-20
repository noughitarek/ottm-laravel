<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrdersImport;
use App\Models\OrderProducts;
use Illuminate\Console\Command;
use App\Models\FacebookConversation;
use Illuminate\Support\Facades\Auth;

class UploadOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-orders-command';

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
        $unUploadedOrders = OrdersImport::whereNotNull('validated_at')->whereNull('uploaded_at')->orderBy('id', 'desc')->get();
        foreach($unUploadedOrders as $orderImport)
        {
            $conversation = null;
            
            if($orderImport->intern_tracking != null)
            {
                $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($orderImport) {
                    $query->select('conversation')
                    ->from('facebook_messages')
                    ->where('message', 'like', '%'.$orderImport->intern_tracking.'%')
                    ->groupBy('conversation')
                    ->get();
                })->first();
            }
            if(!$conversation)
            {
                $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($orderImport) {
                    $query->select('conversation')
                    ->from('facebook_messages')
                    ->where('message', 'like', '%'.$orderImport->phone.'%')
                    ->groupBy('conversation')
                    ->get();
                })->first();
            }
            
            $orderData = $orderImport->toArray();
            $orderData['phone'] = preg_replace('/[^0-9]/', '', $orderImport->phone);
            $orderData['phone2'] = preg_replace('/[^0-9]/', '', $orderImport->phone2);
            if($conversation)
            {
                $orderData['conversation'] = $conversation->facebook_conversation_id;
            }
            if($orderImport->intern_tracking == null)
            {
                $orderData['intern_tracking'] = "NaN";
            }
            $order = Order::create($orderData);
            $orderImport->uploaded_at = now();
            $orderImport->save();

            $charactersToRemove = array("-", "/", "\\");
            $shouldBreak = false;
            foreach(explode('+', $orderData["products"]) as $product)
            {
                $product = trim(str_replace($charactersToRemove, "", $product));
                $productQuantity = 1;
                $productRow = null;
                foreach(config('settings.quantities') as $quantity=>$label)
                {
                    if($label != null)
                    {
                        if(strpos($product, $label) === 0)
                        {
                            $productQuantity = $quantity;
                            $productsName = trim(explode($label, $product)[1]);
                            $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
                        }
                    }

                }
                if(!$productRow)
                {
                    $productsName = $product;
                    $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
                }
                if(!$productRow)
                {
                    $shouldBreak = true;
                    break;
                }
                OrderProducts::create([
                    'order' =>  $order->id,
                    'product' => $productRow->id,
                    'quantity' => $productQuantity,
                ]);
            }
            if($shouldBreak)
            {
                continue;
            }

            if($orderImport->upload)
            {
                if($orderImport->from_stock)
                {
                    for ($i = 0; $i < 3; $i++) {
                        if ($order->Add_To_Ecotrack_Stock()) {
                            break;
                        }
                    }
                }
                else
                {
                    for ($i = 0; $i < 3; $i++) {
                        if ($order->Add_To_Ecotrack()) {
                            break;
                        }
                    }
                }
                if($orderImport->validate)
                {
                    for ($i = 0; $i < 3; $i++) {
                        if ($order->Validate_Ecotrack()) {
                            break;
                        }
                    }
                }
            }
        }
    }
}
