<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\BookExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\FromCollection;


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
            'role' => 'required|in:admin,petugas,peminjam', // Tambahkan validasi untuk peran
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => $request->role, // Simpan peran yang dipilih
        ]);

        return redirect('/login')->with('success', 'berhasil membuat akun!');
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

        // Periksa apakah buku sudah dipinjam sebelumnya
        if ($book->isBorrowed()) {
            return redirect()->back()->with('error', 'Buku sudah dipinjam.');
        }

        // Simpan data peminjaman buku ke dalam tabel 'borrows'
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

            Collection::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->delete();

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
        $reviews = $book->review;

        // Periksa apakah pengguna telah meminjam buku tersebut
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


    public function collection()
    {
        return Book::all();
    }

    public function exportBooks()
    {
        return Excel::download(new BookExport, 'books.xlsx');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Peran pengguna berhasil diperbarui.');
    }

    public function review($id)
    {
        $book = Book::findOrFail($id);
        $borrow = Collection::where('user_id', auth()->id())->where('book_id', $book->id)->first();

        $averageRating = $book->review()->avg('rating');

        $reviewUser = Review::where('book_id', $id)->where('user_id', auth()->id())->first();
        $rating = ($reviewUser) ? $reviewUser->rating : 0; // Jika ada review, ambil rating, jika tidak, set default ke 0

        return view('dashboarduser', compact('book', 'rating', 'reviewUser', 'averageRating', 'borrow'));
    }


    //     $bookId = $request->input('book_id'); // Mengambil ID buku dari input form

    //     // Cek apakah pengguna sudah meminjam buku tersebut
    //     $userHasBorrowed = Borrow::where('user_id', auth()->id())
    //         ->where('book_id', $bookId)
    //         ->exists();

    //     if (!$userHasBorrowed) {
    //         return redirect()->back()->with('error', 'Anda harus meminjam buku ini sebelum memberikan review.');
    //     }

    //     // Cek apakah pengguna sudah memberikan review untuk buku yang ditentukan
    //     $existingReview = Review::where('user_id', auth()->id())
    //                             ->where('book_id', $bookId)
    //                             ->first();

    //     if ($existingReview) {
    //         // Jika review sudah ada, update rating dan review
    //         $existingReview->update([
    //             'rating' => $request->rating,
    //             'review' => $request->review,
    //         ]);
    //     } else {
    //         // Jika review belum ada, buat review baru
    //         Review::create([
    //             'user_id' => auth()->id(),
    //             'book_id' => $bookId,
    //             'rating' => $request->rating,
    //             'review' => $request->review,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Review berhasil disimpan.');
    // }


    // Controller
    public function simpanReview(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|max:255',
        ]);

        $existingReview = Review::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->first();

        // Jika pengguna telah memberikan ulasan, update ulasan yang ada
        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
        } else {
            // Jika belum, buat ulasan baru
            Review::create([
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
        }

        return redirect()->back()->with('success', 'Review saved successfully!');
    }


    public function addToCollection($bookId)
    {
        $userId = Auth::id();
        $book = Book::findOrFail($bookId);
        $user = Auth::user();

        if (!$user->books->contains($bookId)) {
            Collection::create([
                'user_id' => $userId,
                'book_id' => $bookId,
            ]);

            $user->books()->attach($book);

            return redirect()->route('mycollection')->with('success', 'Book added to your collection.');
        } else {
            return redirect()->route('dashboarduser')->with('error', 'Book is already in your collection.');
        }
    }



    public function mycollection()
    {
        $user = Auth::user();
        $collectionBooks = $user->books()->distinct()->get();

        $categories = Category::all();
        return view('mycollection', compact('collectionBooks', 'categories'));
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
        $userId = auth()->id();
        $books = Book::whereDoesntHave('borrows', function ($query) {
            $query->where('status', 'borrowed');
        })->orWhereHas('collections', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return view('dashboarduser', compact('books'));
    }

    private function generatePDF($view, $data, $filename)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view($view, $data)->render();
        $dompdf->loadHtml($html);

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

}


