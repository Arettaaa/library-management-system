<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\Resquest;

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
        'email' => 'required',
        'password' => 'required',
        'address' => 'required',
        'role' => 'required|in:admin, petugas, peminjam',
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

     public function edituser(Request $request,$id)
     {
        $user = User::findOrFail($id);
        return view('edituser');
     }

     public function editcategory(Request $request,$id)
     {
        $ccategory = Category::findOrFail($id);
        return view('editcategory');
     }

     public function deleteuser($id)
     {
        User::where('id', '=', $id)->delete();
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
            'username' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('username', 'password'))) {
            $role = auth()->user()->role;

            if ($role === 'admin' || $role === 'petugas') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboarduser');
            }
        } else {
            return redirect('/dashboarduser');
        }
    }

    // public function auth(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'password' => 'required|password',
    //         'username' => 'required',
    //     ]);


    //     if (auth()->attempt($request->only('username', 'password')))

    //     $credentials= $request->only('password', 'username');

    //     if (Auth::attempt($credentials)) {
    //         return redirect()->intended('/dashboard');
    //     }

    //     return back();
    // }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }



    public function InputCategory(Request $request)
    {
        $request->validate([
        'name' => 'required|min:4|max:50',
        ]);

        Category::create([
        'name' => $request->name,
        ]);

        return redirect('/book')->with('success', 'berhasil membuat akun!');
    }

    public function inputBook(Request $request)
    {
    $request->validate([
    'title' => 'required|min:4|max:50',
    'writer' => 'required',
    'publisher' => 'required',
    'pubyear' => 'required|integer',
    'category_id' => 'required',
    ]);

    $pubyear = intval($request->pubyear);

    Book::create([
    'title' => $request->title,
    'writer' => $request->writer,
    'publisher' => $request->publisher,
    'pubyear' => $pubyear,
    'category_id' => $request->category_id,
    ]);

    return redirect('/book')->with('success', 'berhasil membuat akun!');
    }

    public function deletebook($id)
    {
       Book::where('id', '=', $id)->delete();
       return redirect()->back();
    }

    public function borrowBook($bookId)
    {
        $userId = Auth::id();
        $book = Book::findOrFail($bookId);

        if ($book->isBorrowed()) {
        return redirect()->back();
    }

     Borrow::create([
        'user_id' => $userId,
        'book_id' => $bookId,
        'tanggal_peminjaman' => now(),
        'status' => 'borrowed',
     ]);

     return redirect()->route('borrowed');
    }

    public function collect($bookId)
    {
        $userId = Auth::id();
        $book = Book::findOrFail($bookId);
        // $userId = auth()->id();
        if (!$book) {
        return redirect()->back();
    }

    if ($book->isInCollect($userId)) {
        return redirect()->back();
    }

     Collection::create([
        'user_id' => $userId,
        'book_id' => $bookId,
     ]);

     return redirect()->route('borrowed');
    }

//     public function borrowBook($bookId)
// {
//     if (!Auth::check()) {
//         return redirect()->route('login');
//     }

//     $userId = Auth::id();
//     $book = Book::findOrFail($bookId);

//     if ($book->isBorrowed()) {
//         return redirect()->back();
//     }

//     Borrow::create([
//         'user_id' => $userId,
//         'book_id' => $bookId,
//         'tanggal_peminjaman' => now(),
//         'status' => 'borrowed',
//     ]);

//     return redirect()->route('borrowed');
// }


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

    public function borrowed()
    {
        $user_id = auth()->id();

        $books = Borrow::where('user_id', $user_id)
        ->with('book')
        ->get();

        return view('borrowed', compact('books'));
    }

    public function borrowed_admin()
    {
    $books = Borrow::with('book')
    ->get();

    return view('borrowed_admin', compact('books'));
    }

    public function dashboarduser()
    {
    $books = Book::all();
    return view('dashboarduser', compact('books'));
    }

    public function collectBook($bookId)
{
    $userId = Auth::id();

    $book = Book::findOrFail($bookId);

    if (!$book) {
        return redirect()->back();
    }

    if ($book->isInCollect($userId)) {
        return redirect()->back();
    }

    Collection::create([
        'user_id' => $userId,
        'book_id' => $bookId,
    ]);

    return redirect()->route('borrowed');
}


}

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         return redirect()->intended('/dashboard');
    //     }

    //     return back();
    // }

    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect()->route('login');
    // }

