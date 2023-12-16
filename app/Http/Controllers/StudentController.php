<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Delete Student!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $searchQuery = $request->search;

        if ($searchQuery) {
            $students = Student::where('full_name', 'like', '%' . $searchQuery . '%')
                                ->orWhere('std_id', 'like', '%' . $searchQuery . '%')
                                ->orWhere('phone', 'like', '%' . $searchQuery . '%')
                                ->orWhere('email', 'like', '%' . $searchQuery . '%')
                                ->latest()
                                ->paginate(20);
            $students->appends(['search' => $searchQuery]);
        } else {
            $students = Student::latest()->paginate(20);
        }
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
                Alert::success('Success', "Student created successfully. {$student->std_id}");
                return redirect()->back();
            } else {
                Alert::error('Error', "Please fill correct information.");
                return redirect()->route("students.index");
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt',
        ]);
        // dd($request->csv_file);
        if ($validator->fails()) {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $file = $request->file('csv_file');
            // $mime = $file->getMimeType();
            // dd($mime);
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $requiredFields = ['full_name', 'phone', 'email'];
            $csvHeader = $csv->getHeader();

            foreach ($requiredFields as $requiredField) {
                if (!in_array($requiredField, $csvHeader)) {
                    Alert::error('Error', "The required field '{$requiredField}' is missing in the CSV file.");
                    return redirect()->back();
                }
            }
            foreach ($csv->getRecords() as $record) {
                $existingStudent = Student::where('email', $record['email'])->first();

                if (!$existingStudent) {
                    Student::create([
                        'full_name' => $record['full_name'],
                        'phone' => $record['phone'],
                        'email' => $record['email'],
                        'std_id' =>  $this->generateUniqueStudentID(),
                    ]);
                }
            }
            Alert::success('Success', "Students Uploaded successfully.");
            return redirect()->back();
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "new_name" => "required|string",
            "id" => "required|numeric",
            "new_email" => "required|unique:students,email,{$request->id}",
            'new_phone' => 'required|regex:/^(\+?\d{1,4}[\s-]?)?\(?\d{1,4}\)?[\s.-]?\d{1,10}$/',
        ]);
        if ($validator->fails()) {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $student = Student::find($request->id);
            if (isset($student)) {
                $student->update([
                    'full_name' => $request->new_name,
                    'email'=> $request->new_email,
                    'phone'=> $request->new_phone,
                ]);
                Alert::success('Success', "Student updated successfully.");
                return redirect()->route("students.index");
            }else {
                Alert::error('Error', "Please fill correct information.");
                return redirect()->route("students.index");
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::where('std_id', $id)->first();
        if (isset($student)) {
            $student->delete();
            Alert::success('Success', "Student deleted successfully.");
            return redirect()->route("students.index");
        }else{
            Alert::error('Error', "Student not found.");
            return redirect()->back();
        }
    }
    /**
     * Generate Student ID
     */
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
