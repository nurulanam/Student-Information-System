<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(){
        $programs = Program::all();
        return view("enrollments", compact("programs"));
    }
}
