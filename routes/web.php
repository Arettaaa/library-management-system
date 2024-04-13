<?php

use App\Http\Controllers\PerpusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/






Route::middleware('isGuest')->group(function () {
    Route::get('/register', [PerpusController::class, 'register'])->name('register');
    Route::post('/register', [PerpusController::class, 'inputRegister'])->name('register.post');
    Route::get('/', [PerpusController::class, 'login'])->name('login');
    Route::get('/login', [PerpusController::class, 'login'])->name('login');
    Route::post('/login', [PerpusController::class, 'auth'])->name('login.auth');
});


Route::middleware(['isLogin', 'CekRole:admin,petugas,peminjam'])->group(function () {
    Route::get('/dashboarduser', [PerpusController::class, 'dashboarduser'])->name('dashboarduser');
    Route::get('/book/{id}', [PerpusController::class, 'show'])->name('book.show');
});

Route::middleware(['isLogin', 'CekRole:admin,petugas'])->group(function () {
    Route::get('/dashboard', [PerpusController::class, 'dashboard'])->name('dashboard');
    Route::get('/book', [PerpusController::class, 'book'])->name('book');
    Route::post('/book/category', [PerpusController::class, 'inputCategory'])->name('input.category');
    Route::get('/editcategory/{id}', [PerpusController::class, 'editcategory'])->name('editcategory');
    Route::patch('/updatecategory/{id}', [PerpusController::class, 'updatecateg'])->name('updatecateg');
    Route::delete('/deletecateg/{id}', [PerpusController::class, 'destroycat'])->name('deletecateg');
    Route::post('/createbook', [PerpusController::class, 'inputBook'])->name('input.book');
    Route::get('/createbook', [PerpusController::class, 'createbook'])->name('createbook');
    Route::get('/editbook/{id}', [PerpusController::class, 'editbook'])->name('editbook');
    Route::patch('/update/{id}', [PerpusController::class, 'updatebook'])->name('updatebook');
    Route::delete('/delete/{id}', [PerpusController::class, 'destroy'])->name('delete');
    Route::get('/category/export-pdf', [PerpusController::class, 'exportCatePDF'])->name('categories.export.pdf');
    Route::get('/books/export-pdf', [PerpusController::class, 'exportBooksPDF'])->name('books.export.pdf');
});

Route::middleware('isLogin', 'CekRole:admin')->group(function () {
    Route::get('/registeruser', [PerpusController::class, 'registeruser'])->name('registeruser');
    Route::post('/registeruser', [PerpusController::class, 'RegisUser'])->name('register.user');
    Route::get('/edituser/{id}', [PerpusController::class, 'edituser'])->name('user.edit');
    Route::patch('/update/{id}', [PerpusController::class, 'updateuser'])->name('user.update');
    Route::delete('/deleteuser/{id}', [PerpusController::class, 'destroyuser'])->name('deleteuser');
    Route::get('/userdata', [PerpusController::class, 'userdata'])->name('userdata');
    Route::put('/update-role/{id}', [PerpusController::class, 'updateRole'])->name('update.role');
    Route::get('/borrowed_admin', [PerpusController::class, 'borrowed_admin'])->name('borrowedadm');
    Route::get('/borrows/export-pdf', [PerpusController::class, 'exportBorrowsPDF'])->name('borrows.export.pdf');
    Route::get('/user/export-pdf', [PerpusController::class, 'exportUserPDF'])->name('users.export.pdf');
});

Route::middleware('isLogin', 'CekRole:peminjam')->group(function () {
    Route::post('simpanreview', [PerpusController::class, 'simpanreview'])->name('review.book');
    Route::post('simpanreview/update/{id}', [PerpusController::class, 'simpanreview'])->name('update.review');
    Route::get('/mycollection/{book}', [PerpusController::class, 'mycollection'])->name('mycollection');
    Route::post('/borrow-book/{book}', [PerpusController::class, 'borrowBook'])->name('borrow.book');
    Route::post('/return-book/{bookId}', [PerpusController::class, 'returnBook'])->name('return.book');
    Route::post('/dashboarduser', [PerpusController::class, 'simpanreview'])->name('simpan.review');
    Route::post('/add-to-collection/{id}', [PerpusController::class, 'addToCollection'])->name('add.to.collection');
    Route::get('/mycollection', [PerpusController::class, 'myCollection'])->name('mycollection');
    Route::get('/borrowed/{book}', [PerpusController::class, 'borrowed'])->name('borrowed');
    Route::get('/borrowed', [PerpusController::class, 'borrowed'])->name('borrowed');
});



Route::get('/error', [PerpusController::class, 'error'])->name('error');
Route::get('/logout', [PerpusController::class, 'logout'])->name('logout');
