<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $roles = Role::all();

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view("users", compact("users", 'roles'));
    }
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            "name"=> "required|string",
            "email"=> "required|unique:users,email",
            "role"=> "required|exists:roles,name",
            "password"=> "required|min:8",
            "confirm_password"=> "required|same:password",
        ]);

        if ($validator->fails()) {
            Alert::error('Error', "Please fill correct information");
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user = User::create([
                "name"=> $request->name,
                "email"=> $request->email,
                "password"=> Hash::make($request->password)
            ]);
            if (isset($user)) {
                $user->assignRole($request->role);
                $user->update(['role' => $request->role]);
                Alert::success('Success', "User created as $request->role");
                return redirect()->route("users.index");
            }else{
                Alert::error('Error', "Please fill correct information");
                return redirect()->route("users.index");
            }
        }
    }
    public function destroy($id){
        $user = User::find($id);
        if (isset($user)) {
            $user->delete();
            Alert::success('Success', "User deleted");
            return redirect()->route("users.index");
        }else{
            Alert::error('Error', "User not found");
            return redirect()->back();
        }
    }
}
