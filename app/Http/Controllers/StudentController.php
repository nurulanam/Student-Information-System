<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Delete Student!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $students = Student::all();
        return view('students', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            "email"=> "required|unique:students,email",
            'phone' => 'required|regex:/^(\+?\d{1,4}[\s-]?)?\(?\d{1,4}\)?[\s.-]?\d{1,10}$/',
        ]);
        if ($validator->fails()) {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $student = Student::create([
                "full_name"=> $request->name,
                "email"=> $request->email,
                "phone"=> $request->phone,
                'std_id'=> $this->generateUniqueStudentID()
            ]);
            if (isset($student)) {
                Alert::success('Success', "Student created successfully. {$this->generateUniqueStudentID()} {$request->phone}");
                return redirect()->back();
            } else {
                Alert::error('Error', "Please fill correct information.");
                return redirect()->route("students.index");
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function generateUniqueStudentID()
    {
        $prefix = 'ST';
        $timestamp = now()->timestamp; // Get the current timestamp

        // Combine the prefix, timestamp, and a random number for uniqueness
        $uniqueID = $prefix . $timestamp . mt_rand(1000, 9999);

        while (Student::where('std_id', $uniqueID)->exists()) {
            $uniqueID = $prefix . $timestamp . mt_rand(1000, 9999);
        }

        return $uniqueID;
    }
}
