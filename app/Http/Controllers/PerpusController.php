<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use App\Models\Review;
use App\Exports\BooksExport;
use App\Exports\UsersExport;
use App\Exports\CategoriesExport;
use App\Exports\BorrowsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

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


    public function bookex()
    {
        $categories = Category::all();
        $books = Book::all();
        return view('bookex', compact('categories', 'books'));
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
            'stock' => 'required',
        ]);

        $pubyear = intval($request->pubyear);

        Book::create([
            'title' => $request->title,
            'writer' => $request->writer,
            'publisher' => $request->publisher,
            'pubyear' => $pubyear,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
        ]);

        return redirect('/book')->with('success', 'berhasil membuat akun!');
    }

    public function borrowBook($bookId)
    {
        $userId = Auth::id();
        $book = Book::findOrFail($bookId);

        if ($book->stock <= 0) {
            return redirect()->back();
        }

        Borrow::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'tanggal_peminjaman' => now(),
            'status' => 'borrowed',
        ]);

        $book->decrement('stock');

        return redirect()->route('borrowed');
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

            $book = Book::find($bookId);
            $book->stock += 1;
            $book->save();

            return redirect('/dashboarduser');
        }
        return redirect('/borrowed');
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
            'stock' => 'required',
        ]);

        $pubyear = intval($request->pubyear);

        Book::where('id', $id)->update([
            'title' => $request->title,
            'writer' => $request->writer,
            'publisher' => $request->publisher,
            'pubyear' => $pubyear,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
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

    public function updateuser(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|min:4|max:255',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|max:255',
            'role' => 'required|in:admin,petugas,peminjam',
            'password' => 'nullable',
        ]);

        $user = User::findOrFail($id);
        $data = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'role' => $request->role,
        ];

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect('/userdata')->with('success', 'User successfully updated.');
    }

    public function destroyuser($id)
    {
        User::where('id', '=', $id)->delete();
        return redirect()->back();
    }

    public function simpanReview(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|max:255',
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
        $user = Auth::user();

        $book = Book::findOrFail($bookId);

        if ($book->isInCollection($userId)) {
            return redirect()->route('dashboarduser');
        }

        Collection::create([
            'user_id' => $userId,
            'book_id' => $bookId,
        ]);

        return redirect()->route('mycollection');
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
        $books = Book::all();
        return view('dashboarduser', compact('books'));
    }

    private function generatePDF($view, $data, $filename)
    {

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view($view, $data)->render());
        $dompdf->setPaper('A4', 'portrait');
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

    public function searchBooks(Request $request)
    {
        $cari = $request->input('cari');

        $books = Book::whereHas('category', function ($query) use ($cari) {
            $query->where('name', 'like', "%$cari%");
        })
            ->orWhere('title', 'like', "%$cari%")
            ->orWhere('writer', 'like', "%$cari%")
            ->orWhere('publisher', 'like', "%$cari%")
            ->orWhere('pubyear', 'like', "%$cari%")
            ->get();

            return view('search', compact('books', 'cari'));
        }

    public function cariBooks(Request $request)
    {
        $cari = $request->input('cari');

        $books = Book::whereHas('category', function ($query) use ($cari) {
            $query->where('name', 'like', "%$cari%");
        })
            ->orWhere('title', 'like', "%$cari%")
            ->orWhere('writer', 'like', "%$cari%")
            ->orWhere('publisher', 'like', "%$cari%")
            ->orWhere('pubyear', 'like', "%$cari%")
            ->get();

        return view('dashboarduser', compact('books', 'cari'));
    }

    public function search(Request $request)
    {

        $categories = Category::all();
        $books = Book::all();
        return view('search', compact('categories', 'books'));    
    }

        public function exportBooks(Request $request)
        {
            $cari = $request->input('cari');
        
            $books = Book::where(function ($query) use ($cari) {
                    $query->where('title', 'like', "%$cari%")
                          ->orWhere('writer', 'like', "%$cari%")
                          ->orWhere('publisher', 'like', "%$cari%")
                          ->orWhere('pubyear', 'like', "%$cari%");
                })
                ->orWhereHas('category', function ($query) use ($cari) {
                    $query->where('name', 'like', "%$cari%");
                })
                ->get();
        
            return Excel::download(new BooksExport($books), 'books.xlsx');
        }
        

    public function exportCategories()
    {
        return Excel::download(new CategoriesExport(), 'categories.xlsx');
    }

    public function exportUsers()
    {
        return Excel::download(new UsersExport(), 'users.xlsx');
    }

    public function exportBorrows()
    {
        return Excel::download(new BorrowsExport(), 'borrows.xlsx');
    }



}


