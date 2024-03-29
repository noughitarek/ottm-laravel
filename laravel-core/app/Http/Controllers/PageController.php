<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function privacy_policy()
    {
        return view('privacy-policy');
    }
}
