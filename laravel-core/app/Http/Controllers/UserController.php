<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('deleted_at', null)->paginate(20)->onEachSide(2);
        return view('pages.users')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'permissions' => implode(',',$request->input('permissions')),
        ]);
        return back()->with("success", "user has been created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'permissions' => implode(',',$request->input('permissions')),
        ]);
        return back()->with("success", "user has been updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->update([
            'email' => '',
            'password' => '',
            'permissions' => '',
            'deleted_at' => now()
        ]);
        return back()->with("success", "user has been deleted successfully");
    }
}
