<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Http\Requests\StoreDeskRequest;
use App\Http\Requests\UpdateDeskRequest;

class DeskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $desks = Desk::where('deleted_at', null)->paginate(20)->onEachSide(2);
        return view('pages.desks')->with('desks', $desks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeskRequest $request)
    {
        $desk = Desk::create([
            'name' => $request->input('name'),
            'ecotrack_link' => $request->input('ecotrack_link'),
            'ecotrack_token' => $request->input('ecotrack_token'),
        ]);
        return back()->with("success", "Desk has been created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeskRequest $request, Desk $desk)
    {
        $desk->update([
            'name' => $request->input('name'),
            'ecotrack_link' => $request->input('ecotrack_link'),
            'ecotrack_token' => $request->input('ecotrack_token'),
        ]);
        return back()->with("success", "Desk has been updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desk $desk)
    {
        $desk->update(['deleted_at'=> now()]);
        return back()->with("success", "Desk has been deleted successfully");
    }
}
