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
        $unUploadedOrders = OrdersImport::whereNull('uploaded_at')->orderBy('id', 'desc')->get();
        foreach($unUploadedOrders as $orderImport)
        {
            $conversation = FacebookConversation::whereIn('facebook_conversation_id', function($query) use ($orderImport) {
                $query->select('conversation')
                ->from('facebook_messages')
                ->where('message', 'like', '%'.$orderImport->intern_tracking.'%')
                ->groupBy('conversation')
                ->get();
            })->first();
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
            $order = Order::create($orderData);
            $orderImport->uploaded_at = now();
            $orderImport->save();

            $charactersToRemove = array("-", "/", "\\");
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
                            if(!$productRow)
                            {
                                $productRow = Product::create([
                                    'name' => $productsName,
                                    'slug' => $productsName,
                                    'created_by' => $orderImport->created_by
                                ]);
                            }
                            break;
                        }
                    }

                }
                if(!$productRow)
                {
                    $productsName = $product;
                    $productRow = Product::where('name', 'like', '%'.$productsName.'%')->where('deleted_at', null)->first();
                    if(!$productRow)
                    {
                        $productRow = Product::create([
                            'name' => $productsName,
                            'slug' => $productsName,
                            'created_by' => $orderImport->created_by
                        ]);
                    }
                }
                OrderProducts::create([
                    'order' =>  $order->id,
                    'product' => $productRow->id,
                    'quantity' => $productQuantity,
                ]);
            }
        }
    }
}
