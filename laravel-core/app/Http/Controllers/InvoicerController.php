<?php

namespace App\Http\Controllers;

use App\Models\Wilaya;
use App\Models\Invoicer;
use Illuminate\Http\Request;
use App\Imports\InvoicesImport;
use App\Models\InvoicerProducts;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InvoicerOrdersProducts;
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
        $wilayas = Wilaya::all();
        return view('pages.invoicer.index')->with('products', $products)->with('wilayas', $wilayas);
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
            'purchase_price' => $request->purchase_price,
        ]);
        return back()->with("success", "product has been created successfully");
    }
    public function products_store_all(Request $request)
    {
        foreach($request->input('products') as $product)
        {
            if($product['id']==$product['same_as'])
            {
                $existingProduct = InvoicerProducts::find($product['id']);
                $existingProduct->update([
                    'purchase_price' => $product['purchase_price'],
                    'min_price' => $product['min_price'],
                    'max_price' => $product['max_price'],
                    'confirmed' => true
                ]);
            }
            else
            {
                $existingProduct = InvoicerProducts::find($product['same_as']);
                $existingProduct->update([
                    'slug' => $existingProduct->slug.', '.$product['slug']
                ]);
                InvoicerOrdersProducts::where('product', $product['id'])->update(['product'=>$existingProduct->id]);

            }
        }
        InvoicerProducts::where('confirmed', false)->delete();
        return redirect()->route('invoicer_invoice', $request->input('invoice'));
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

        $invoice = Invoicer::create([]);
        $import = new InvoicesImport($invoice->id);
        Excel::import($import, $filePath);
        

        $products = InvoicerProducts::where('deleted_at', null)->where('confirmed', false)->get();

        if($products->isEmpty())
        {
            return redirect()->route('invoicer_invoice', $invoice->id);
        }
        else
        {
            $all_products = InvoicerProducts::where('deleted_at', null)->get();
            return view('pages.invoicer.create_products')
            ->with('products', $products)->with('invoice', $invoice)->with('all_products', $all_products);
        }
    }

    /**
     * Display the specified resource.
     */
    public function invoice(Invoicer $invoice)
    {
        return view('pages.invoicer.invoice')->with('invoice', $invoice);
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
        $product->update(['slug'=>null, 'deleted_at' => now()]);
        return back()->with("success", "product has been deleted successfully");
    }
}
