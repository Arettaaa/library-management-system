<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerpusController extends Controller
{
    public function register()
    {
        return view ('register');
    }

    public function inputRegister(Request $request)
        {
        $request->validate([
        'name' => 'required|min:4|max:50',
        'username' => 'required|min:4|max:8',
        'password' => 'required',
        'address' => 'required',
        ]);

        User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'address' => $request->address,
        'role' => 'peminjam',
        ]);

        return redirect('/login');
    }

    public function registeruser()
    {
        return view ('registeruser');
    }

    public function InputUser(Request $request)
        {
        $request->validate([
        'name' => 'required',
        'username' => 'required',
        'password' => 'required',
        'address' => 'required',
        'role' => 'required|in:admin, petugas,peminjam',
        ]);

        User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'address' => $request->address,
        'role' => $request->role,
        ]);

        return redirect('/login');
    }

    public function UpdateUser(Request $request, $id)
     {
      $user = User::findOrFail('id');
      $data = [
       'name' => $request->name,
       'username' => $request->username,
       'email' => $request->email,
       'address' => $request->address,
       'role' => $request->role,
      ];

      if ($request->has('password')){
       $data['password']=bcrypt($request->password);
      }

      $user->update($data);

      return redirect('/userdata');
     }

     public function edituser(Resquest $request,$id)
     {
        $user = User::findOrFail($id);
        return view('edituser');
     }

     public function deleteuser($id)
     {
        User::where('id', '=', $id)->delte();
        return redirect()->back();
     }

     public function userdata()
    {
        $users = User::all();
        return view('userdata', compact('users'));
    }

    public function login()
    {
    return view('login');
    }


    public function auth(Request $request)
    {
    $request->validate([
    'username' => 'required|min:4|max:8',
    'password' => 'required',
    ]);

    $user = $request->only('username', 'password');
    if (Auth::attempt($user)) {
    $role = Auth::user()->role;

    if ($role === 'admin' || $role === 'petugas') {
    return redirect()->route('dashboard');
    } else {
    return redirect()->route('dashboarduser');
    }
    } else {
    return redirect('/dashboarduser');
    }
    }


    public function createbook()
    {
        $categories = Category::all();
        return view('createbook', compact('categories'));
    }

    public function book()
    {
        $categories = Category::all();
        $books = Book::all();
        return view('book', compact('categories', 'books'));
    }

    public function inputCategory(Request $request)
    {
        $request->validate([
        'name' => 'required|min:4|max:50',
        ]);

        Category::create([
        'name' => $request->name,
        ]);

        return redirect('/book')->with('success', 'berhasil membuat akun!');
    }

}
