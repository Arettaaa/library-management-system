<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerpusController extends Controller
{
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
            return redirect()->route('dashboard');
        } else {
            return redirect('/login')->with('fail', "Gagal login, periksa dan coba lagi!");
        }
    }

    public function register()
    {
        return view('register');
    }

    public function registeradmin()
    {
        return view('registeradmin');
    }

    public function registerpetugas()
    {
        return view('registerpetugas');
    }

    public function RegisAdmin(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'email' => 'required',
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
            'role' => 'admin',
        ]);

        return redirect('/dashboard')->with('success', 'berhasil membuat akun!');
    }

    public function RegisPetugas(Request $request)
    {
        $request->validate([
            'email' => 'required',
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
            'role' => 'petugas',
        ]);

        return redirect('/dashboard')->with('success', 'berhasil membuat akun!');
    }

    public function inputRegister(Request $request)
    {
        $request->validate([
            'email' => 'required',
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

        return redirect('/login')->with('success', 'berhasil membuat akun!');
    }


    public function dashboard()
    {
        return view('dashboard');
    }

    public function dashboarduser()
    {
        $categories = Category::all();
        $books = Book::whereDoesntHave('borrows', function ($query) {
            $query->where('status', 'borrowed');
        })->get();

        return view('dashboarduser', compact('categories', 'books'));
    }

    public function userdata()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        $officers = User::where('role', 'petugas')->latest()->get();
        return view('userdata', compact('admins', 'officers'));
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

    
    public function borrowBook($bookId)
    {
        $userId = Auth::id();
        $book = Book::findOrFail($bookId);
        $user = Auth::user();

        Borrow::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'tanggal_peminjaman' => now(),
            'status' => 'borrowed',
        ]);

        Collection::create([
            'user_id' => $userId,
            'book_id' => $bookId,
        ]);

        return redirect()->route('mycollection')->with('success', 'Book borrowed successfully!');
    }

    public function mycollection()
    {
        $user = Auth::user();
        $borrowedBooks = $user->books()->wherePivot('status', 'borrowed')->get();
        $categories = Category::all();
        return view('mycollection', compact('borrowedBooks', 'categories'));
    }

    public function returnBook($bookId)
    {
        $userId = Auth::id();
        
        $borrow = Borrow::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->first();
    
        if ($borrow) {
            $borrow->update([
                'status' => 'returned',
                'tanggal_pengembalian' => now(),
            ]);
    
            Collection::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->delete();
    
            return redirect('/dashboarduser')->with('success', 'Book returned successfully.');
        }
    
        return redirect('/mycollection')->with('error', 'Book not found in your collection.');
    }

    public function editcategory($id)
    {
        $category = Category::find($id);
        return view('editcategory', compact('category'));
    }

    public function updatecateg(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::where('id', $id)->update([
            'name' => $request->name,
        ]);

        return redirect('/book')->with('success', 'Category updated successfully');
    }

    public function destroycat($id)
    {
        Category::where('id', '=', $id)->delete();
        return redirect()->back()->with('successDelete', 'Berhasil menghapus');
    }

    public function editbook($id)
    {
        $categories = Category::all();
        $book = Book::where('id', $id)->first();
        return view('editbook', compact('categories', 'book'));
    }

    public function updatebook(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:4|max:50',
            'writer' => 'required',
            'publisher' => 'required',
            'pubyear' => 'required|integer',
            'category_id' => 'required',
        ]);

        $pubyear = intval($request->pubyear);

        Book::where('id', $id)->update([
            'title' => $request->title,
            'writer' => $request->writer,
            'publisher' => $request->publisher,
            'pubyear' => $pubyear,
            'category_id' => $request->category_id,
        ]);

        return redirect('/book')->with('success', 'berhasil membuat akun!');
    }

    public function destroy($id)
    {
        Book::where('id', '=', $id)->delete();
        return redirect()->back()->with('successDelete', 'Berhasil menghapus');
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('detail', compact('book'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

}
