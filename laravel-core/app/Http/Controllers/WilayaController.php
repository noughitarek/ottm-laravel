<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateWilayasRequest;

class WilayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wilayas = Wilaya::all();
        $desks = Desk::Where('deleted_at', null)->get();
        return view('pages.wilayas')->with('wilayas', $wilayas)->with('desks', $desks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWilayasRequest $request)
    {
        foreach($request->wilayas as $wilaya_id=>$wilaya)
        {
            $w = Wilaya::find($wilaya_id);
            $w->update([
                'name_ar' => $wilaya['name_ar'],
                'delivery_price' => $wilaya['delivery_price']??0,
                'desk' => $wilaya['desk']??null
            ]);
        }
        return back()->with("success", "Wilayas has been updated successfully");
    }

    public function auto_update()
    {
        foreach(Desk::all() as $desk)
        {
            $desk->Update_API();
        }
        return back()->with("success", "Wilayas has been updated successfully");
    }
}
