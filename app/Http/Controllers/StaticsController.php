<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticsController extends Controller
{
    public function index(): view
    {
        return view('statics');
    }
}
