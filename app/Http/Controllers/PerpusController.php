<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use App\Models\Review;
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
            $role = Auth::user()->role;

            if ($role === 'admin' || $role === 'petugas') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboarduser');
            }
        } else {
            return redirect('/login');
        }
    }

    public function register()
    {
        return view('register');
    }

    public function registeruser()
    {
        return view('registeruser');
    }

    public function RegisUser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'name' => 'required|min:4|max:50',
            'username' => 'required|min:4|max:8',
            'password' => 'required',
            'address' => 'required',
            'role' => 'required|in:admin,petugas,peminjam', 
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => $request->role,  
        ]);

        return redirect('/userdata')->with('success', 'berhasil membuat akun!');
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
        $adminsCount = User::where('role', 'admin')->count();
        $officersCount = User::where('role', 'petugas')->count();
        $borrowersCount = User::where('role', 'peminjam')->count();
        $books = Book::all();
        return view('dashboard', compact('adminsCount', 'officersCount', 'borrowersCount', 'books'));
    }


    public function userdata()
    {
        $roles = User::where('role', 'admin', 'petugas', 'peminjam');
        $users = User::all();
        return view('userdata', compact('users', 'roles'));
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

        if ($book->isBorrowed()) {
            return redirect()->back()->with('error', 'Buku sudah dipinjam.');
        }

        Borrow::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'tanggal_peminjaman' => now(),
            'status' => 'borrowed',
        ]);

        return redirect()->route('borrowed')->with('success', 'Book borrowed successfully!');
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


            return redirect('/dashboarduser')->with('success', 'Book returned successfully.');
        }

        return redirect('/borrowed')->with('error', 'Book not found in your collection.');
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
        return redirect()->back();
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
        $reviews = $book->review;

        $userHasBorrowedBook = Borrow::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->exists();

        return view('detail', compact('book', 'reviews', 'userHasBorrowedBook'));
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function edituser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('edituser', compact('user'));
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



    public function destroyuser($id)
    {
        User::where('id', '=', $id)->delete();
        return redirect()->back();
    }

    public function simpanReview(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);
    
        Review::updateOrCreate(
            [
                'book_id' => $request->book_id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );
    
        return redirect()->back();
    }
    


    public function addToCollection($bookId)
    {
        $userId = Auth::id();
        $book = Book::findOrFail($bookId);
        $user = Auth::user();

        if (!$user->books()->where('books.id', $bookId)->exists()) {
            Collection::create([
                'user_id' => $userId,
                'book_id' => $bookId,
            ]);

            return redirect()->route('mycollection')->with('success', 'Book added to your collection.');
        } else {
            return redirect()->route('dashboarduser')->with('error', 'Book is already in your collection.');
        }
    }


    public function mycollection()
    {
        $user_id = auth()->id();

        $collectionBooks = Collection::where('user_id', $user_id)
            ->with('book')
            ->get();

        return view('mycollection', compact('collectionBooks'));
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
        $categories = Category::all();
        $books = Book::all();
        return view('dashboarduser', compact('categories', 'books'));
    }

    private function generatePDF($view, $data, $filename)
    {
       $dompdf = new Dompdf();
       $dompdf->loadHtml(view($view, $data)->render());
       $dompdf->setPaper('A4', 'potrait');
       $dompdf->render();
       return $dompdf->stream($filename);
    }

    public function exportBorrowsPDF()
    {
        $borrows = Borrow::all();

        return $this->generatePDF('pdf.borrows', compact('borrows'), 'borrows.pdf');
    }

    public function exportCatePDF()
    {
        $categories = Category::all();

        return $this->generatePDF('pdf.category', compact('categories'), 'categories.pdf');
    }

    public function exportBooksPDF()
    {
        $books = Book::all();

        return $this->generatePDF('pdf.books', compact('books'), 'books.pdf');
    }

    public function exportUserPDF()
    {
        $users = User::all();

        return $this->generatePDF('pdf.user', compact('users'), 'users.pdf');
    }

    public function error()
    {
        return view('error');
    }


}


