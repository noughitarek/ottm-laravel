<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreInvestorRequest;
use App\Http\Requests\UpdateInvestorRequest;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $investors = Investor::whereNull('deleted_at')->get();
        return view('pages.investors.index')->with('investors', $investors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.investors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvestorRequest $request)
    {
        Investor::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return back()->with('success', 'Investor has been created succesfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investor $investor)
    {
        return view('pages.investors.edit')->with('investor', $investor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvestorRequest $request, Investor $investor)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];
    
        if ($request->has('password') && $request->input('password') != '') {
            $data['password'] = Hash::make($request->input('password'));
        }
        $investor->update($data);
        return back()->with('success', 'Investor has been updated succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investor $investor)
    {
        $investor->update(['deleted_at' => now()]);
        return back()->with('success', 'Investor has been deleted succesfully');
    }
}
