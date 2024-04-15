<?php

namespace App\Http\Controllers;

use App\Models\Invoicer;
use App\Imports\InvoicesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreInvoicerRequest;
use App\Http\Requests\UpdateInvoicerRequest;

class InvoicerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filePath = 'storage/invoices/1713047650_ecotrack - Vendeur (1).xlsx';
        if (file_exists($filePath)) {
            $import = new InvoicesImport();
            Excel::import($import, $filePath);
            #$data = $import->getData();
            #print_r($data);
        } else {
            return 'File does not exist.';
        }

        exit;
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
    public function upload(StoreInvoicerRequest $request)
    {
        $photo = $request->file('file');
        $filename = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('storage/invoices'), $filename);
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
}
