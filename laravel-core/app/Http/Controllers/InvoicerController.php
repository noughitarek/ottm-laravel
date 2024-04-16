<?php

namespace App\Http\Controllers;

use App\Models\Invoicer;
use Illuminate\Http\Request;
use App\Imports\InvoicesImport;
use App\Models\InvoicerProducts;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreInvoicerRequest;
use App\Http\Requests\UpdateInvoicerRequest;

class InvoicerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = InvoicerProducts::where('deleted_at', null)->get();
        return view('pages.invoicer.index')->with('products', $products);
        /*$filePath = 'storage/invoices/1713047650_ecotrack - Vendeur (1).xlsx';
        if (file_exists($filePath)) {
            $import = new InvoicesImport();
            Excel::import($import, $filePath);
            #$data = $import->getData();
            #print_r($data);
        } else {
            return 'File does not exist.';
        }*/
        $csrfToken = csrf_field();
        return '<form method="POST" action="'.route('invoicer_upload').'" enctype="multipart/form-data">
        '.$csrfToken.'
            <input type="file" name="file">
            <button type="submit">Upload</button>
        </form>';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function products_store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'slug' => 'required|string|unique:invoicer_products,slug',
            'min_price' => 'nullable|integer',
            'max_price' => 'nullable|integer',
            'quantity_prices' => 'nullable|array',
            'purchase_price' => 'nullable|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with("error", $validator->errors()); 
        }
        InvoicerProducts::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'quantity_prices' => json_encode($request->quantity_prices),
            'purchase_price' => $request->purchase_price,
        ]);
        return back()->with("success", "product has been created successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function upload(StoreInvoicerRequest $request)
    {
        $invoice = $request->file('invoice');
        $filename = time() . '_' . $invoice->getClientOriginalName();
        $invoice->move(public_path('storage/invoices'), $filename);
        $filePath = 'storage/invoices/'.$filename;

        $import = new InvoicesImport();
        Excel::import($import, $filePath);
        $amount = $orders = 0;
        $total = $delivery = $clean = 0;
        foreach($import->getRows() as $order){
            $total += $order->total_price;
            $delivery += $order->delivery_price;
            $clean += $order->clean_price;
            $amount += $order->recovered;
            $orders += 1;
        }
        $invoice = Invoicer::create([
            'total_amount' => $amount,
            'total_orders' => $orders
        ]);
        return view('pages.invoicer.create')
        ->with('orders', $import->getRows())
        ->with('total', $total)
        ->with('delivery', $delivery)
        ->with('clean', $clean)
        ->with('invoice', $invoice);

        exit;
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $path = $file->store('invoices');
            $import = new InvoicesImport();
            Excel::import($import, $file);
            $data = $import->getData();
            print_r($data);
            return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
        }
        return response()->json(['error' => 'File upload failed'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoicer $invoicer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoicer $invoicer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function products_update(Request $request, InvoicerProducts $product)
    {
        $rules = [
            'name' => 'required|string',
            'slug' => 'required|string|unique:invoicer_products,slug,' . $product->id,
            'min_price' => 'nullable|integer',
            'max_price' => 'nullable|integer',
            'quantity_prices' => 'nullable|array',
            'purchase_price' => 'nullable|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with("error", $validator->errors()); 
        }
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'quantity_prices' => json_encode($request->quantity_prices),
            'purchase_price' => $request->purchase_price,
        ]);
        return back()->with("success", "product has been updated successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoicerRequest $request, Invoicer $invoicer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoicer $invoicer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function products_destroy(Request $request, InvoicerProducts $product)
    {
        $product->update(['deleted_at' => now()]);
        return back()->with("success", "product has been deleted successfully");
    }
}
