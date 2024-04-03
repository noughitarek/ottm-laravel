<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Wilaya;
use App\Models\Commune;
use App\Models\Product;
use App\Models\FacebookUser;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FacebookConversation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pending()
    {
        $user = Auth::user();
        if($user->Has_Permission('orders_consult')){
            $orders = Order::Pending()->get();
        }else{
            $orders = Order::Pending()->where('created_by', $user->id)->get();
        }
        return view('pages.orders.table')->with('title', "Pending orders")->with('orders', $orders);
    }
    /**
     * Display a listing of the resource.
     */
    public function towilaya()
    {
        $user = Auth::user();
        if($user->Has_Permission('orders_consult')){
            $orders = Order::ToWilaya()->get();
        }else{
            $orders = Order::ToWilaya()->where('created_by', $user->id)->get();
        }
        return view('pages.orders.table')->with('title', "To Wilaya orders")->with('orders', $orders);
    }

    /**
     * Display a listing of the resource.
     */
    public function delivery()
    {
        $user = Auth::user();
        if($user->Has_Permission('orders_consult')){
            $orders = Order::Delivery()->get();
        }else{
            $orders = Order::Delivery()->where('created_by', $user->id)->get();
        }
        return view('pages.orders.table')->with('title', "Delivery orders")->with('orders', $orders);
    }

    /**
     * Display a listing of the resource.
     */
    public function delivered()
    {
        $user = Auth::user();
        if($user->Has_Permission('orders_consult')){
            $orders = Order::Delivered()->get();
        }else{
            $orders = Order::Delivered()->where('created_by', $user->id)->get();
        }
        return view('pages.orders.table')->with('title', "Delivered orders")->with('orders', $orders);
    }

    /**
     * Display a listing of the resource.
     */
    public function back()
    {
        $user = Auth::user();
        if($user->Has_Permission('orders_consult')){
            $orders = Order::Back()->get();
        }else{
            $orders = Order::Back()->where('created_by', $user->id)->get();
        }
        return view('pages.orders.table')->with('title', "Back orders")->with('orders', $orders);
    }

    /**
     * Display a listing of the resource.
     */
    public function archived()
    {
        $user = Auth::user();
        if($user->Has_Permission('orders_consult')){
            $orders = Order::Archived()->get();
        }else{
            $orders = Order::Archived()->where('created_by', $user->id)->get();
        }
        return view('pages.orders.table')->with('title', "Archived orders")->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $conversations = FacebookUser::Get_Conversations();
        $products = Product::where('deleted_at', null)->get();
        $wilayas = Wilaya::where('desk', "!=", null)->get();
        return view('pages.orders.create')->with('products', $products)->with('wilayas', $wilayas)->with('conversations', $conversations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_from_product(Product $product)
    {
        $conversations = FacebookUser::Get_Conversations();
        $wilayas = Wilaya::where('desk', "!=", null)->get();
        return view('pages.orders.create')->with('product', $product)->with('wilayas', $wilayas)->with('conversations', $conversations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_from_conversation(FacebookUser $conversation)
    {
        $products = Product::where('deleted_at', null)->get();
        $wilayas = Wilaya::where('desk', "!=", null)->get();
        return view('pages.orders.create')->with('products', $products)->with('wilayas', $wilayas)->with('conversation', $conversation);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'phone2' => $request->input('phone2'),
            'commune' => $request->input('commune'),
            'desk' => Wilaya::find($request->input('wilaya'))->desk,
            'address' => $request->input('address'),
            'fragile' => $request->has('fragile'),
            'stopdesk' => $request->has('stopdesk'),
            'description' => $request->input('description'),
            'total_price' => $request->input('total_price'),
            'delivery_price' => $request->input('delivery_price'),
            'clean_price' => $request->input('clean_price'),
            'intern_tracking' => $request->input('intern_tracking'),
            'created_by' => Auth::user()->id,
            'IP' => $_SERVER['REMOTE_ADDR'],
            'conversation' => $request->input('conversation'),
        ]);
        foreach($request->products as $product){
            if(!isset($product['id']) || $product['id']==null)continue;
            OrderProducts::create([
                'order' => $order->id,
                'product' => $product['id'],
                'quantity' => $product['quantity']??1,
            ]);
        }
        if($request->has('add_to_ecotrack'))
        {
            $order->Add_To_Ecotrack();
            if($request->has('validate'))
            {
                $order->Validate_Ecotrack();       
            }
        }
        return back()->with("success", "order has been created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function getCommunes($wilaya)
    {
        $communes = Commune::where('wilaya', $wilaya)->get();
        return response()->json($communes);
    }
    
    public function getDelivery(Wilaya $wilaya)
    {
        return response()->json($wilaya);
    }
}
