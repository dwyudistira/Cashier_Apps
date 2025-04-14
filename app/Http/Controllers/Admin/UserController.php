<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::paginate(10);
        return view("admin.user.index", compact("users"));
    }

    public function create(){
        return view("admin.user.create");
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Administrator,Petugas',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.user');
    }

    public function edit($id){
        $users = User::find($id);

        return view('admin.user.edit', compact("users"));
    }

    public function update(Request $request, $id){
        $users = User::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Administrator,Petugas',
        ]);
        
        $users->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.user');
    }

    public function destroy($id){
        $user = User::find($id);

        $user->delete();

        return redirect()->route('admin.user');
    }
}
