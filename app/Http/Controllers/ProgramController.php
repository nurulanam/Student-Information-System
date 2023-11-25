<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Delete Program!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $programs = Program::paginate(10);
        return view("programs", compact("programs"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'details'=> "required|string",
        ]);
        if ($validator->fails()) {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $program = Program::create([
                'name' => $request->name,
                'details' => $request->details,
            ]);
            $program->save();
            if (isset($program)) {
                Alert::success('Success', "Program created successfully.");
                return redirect()->back();
            } else {
                Alert::error('Error', "Please fill correct information.");
                return redirect()->route("programs.index");
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'new_name' => 'required|string',
            'new_details'=> "required|string",
        ]);
        if ($validator->fails()) {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $program = Program::find($request->id);
            $program->update([
                "name"=> $request->new_name,
                "details"=> $request->new_details,
            ]);
            if(isset($program)){
                Alert::success('Success', "Program update successfully.");
                return redirect()->back();
            } else {
                Alert::error('Error', "Please fill correct information.");
                return redirect()->route("programs.index");
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $program = Program::find($id);
        if (isset($program)) {
            $program->delete();
            Alert::success('Success', "Program deleted successfully.");
            return redirect()->route("programs.index");
        } else {
            Alert::error('Error', "Program not found.");
            return redirect()->route("programs.index");
        }
    }
}
