<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function documentation()
    {
        return view('pages.documentation');
    }
    public function index()
    {
        return view('site.index');
    }
}
