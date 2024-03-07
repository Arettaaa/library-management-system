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

Route::get('/', function () {
    return view('login');
});



Route::get('/registeradmin', [PerpusController::class, 'registeradmin'])->name('registeradmin');
Route::post('/registeradmin', [PerpusController::class, 'RegisAdmin'])->name('RegisAdmin');

Route::get('/registerpetugas', [PerpusController::class, 'registerpetugas'])->name('registerpetuga');
Route::post('/registerpetugas', [PerpusController::class, 'RegisPetugas'])->name('register.petugas');

Route::get('/register', [PerpusController::class, 'register'])->name('register');
Route::post('/register', [PerpusController::class, 'inputRegister'])->name('register.post');

Route::get('/login', [PerpusController::class, 'login'])->name('login');
Route::post('/login', [PerpusController::class, 'auth'])->name('login.auth');

Route::get('/dashboard', [PerpusController::class, 'dashboard'])->name('dashboard');

Route::get('/userdata', [PerpusController::class, 'userdata'])->name('userdata');


Route::get('/book', [PerpusController::class, 'book'])->name('book');
Route::post('/book/category', [PerpusController::class, 'inputCategory'])->name('input.category');
Route::get('/editcategory/{id}', [PerpusController::class, 'editcategory'])->name('editcategory');
Route::patch('/updatecategory/{id}', [PerpusController::class, 'updatecateg'])->name('updatecateg');
Route::get('/dashboarduser', [PerpusController::class, 'dashboarduser'])->name('dashboarduser');
Route::delete('/deletecateg/{id}', [PerpusController::class, 'destroycat'])->name('deletecateg');


Route::post('/createbook', [PerpusController::class, 'inputBook'])->name('input.book');
Route::get('/createbook', [PerpusController::class, 'createbook'])->name('createbook');
Route::get('/editbook/{id}', [PerpusController::class, 'editbook'])->name('editbook');
Route::patch('/update/{id}', [PerpusController::class, 'updatebook'])->name('updatebook');
Route::delete('/delete/{id}', [PerpusController::class, 'destroy'])->name('delete');


Route::post('/borrow-book/{book}', [PerpusController::class, 'borrowBook'])->name('borrow.book');
Route::get('/mycollection', [PerpusController::class, 'mycollection'])->name('mycollection');
Route::post('/return-book/{bookId}', [PerpusController::class, 'returnBook'])->name('return.book');

Route::get('/book/{id}', [PerpusController::class, 'show'])->name('book.show');
