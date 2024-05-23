<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Wilaya;
use App\Models\DeliveryMen;
use Illuminate\Http\Request;

class DeliveryMensController extends Controller
{
    public function index()
    {
        $wilayas = Wilaya::all();
        return view('pages.deliverymens.index')->with('wilayas', $wilayas);
    }
    public function wilaya(Wilaya $wilaya)
    {
        $desks = Desk::where('deleted_at', null)->get();
        return view('pages.deliverymens.wilaya')->with('wilaya', $wilaya)->with('desks', $desks);
    }
    public function edit_wilaya(Wilaya $wilaya, Request $request)
    {
        foreach($request->desk as $desk=>$communes)
        {
            foreach($communes as $commune=>$phone_number)
            {

                $dm = DeliveryMen::where('desk', $desk)->where('commune', $commune)->first();
                if($dm)
                {
                    $dm->update([
                        'desk' => $desk,
                        'phone_number' => $phone_number,
                        'commune' => $commune
                    ]);
                }
                else
                {
                    DeliveryMen::create([
                        'desk' => $desk,
                        'phone_number' => $phone_number,
                        'commune' => $commune
                    ]);
                }
            }
        }
        return back()->with('success', 'Phone numbers has been updated successfully');
    }
}
