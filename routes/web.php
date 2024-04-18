<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerpusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [PerpusController::class, 'register'])->name('register');
Route::post('/register', [PerpusController::class, 'InputRegister'])->name('register.post');

Route::get('/registeruser', [PerpusController::class, 'registeruser'])->name('registeryuser');
Route::post('/registeruser', [PerpusController::class, 'InputUser'])->name('register.user');
Route::get('/edituser/{id}', [PerpusController::class, 'edituser'])->name('edituser');
Route::patch('/updateuser{id}', [PerpusController::class, 'UpdateUser'])->name('user.update');
Route::delete('/deleteuser/{id}', [PerpusController::class, 'destroyuser'])->name('deleteuser');

Route::get('/', [PerpusController::class, 'login'])->name('login');
Route::post('/login', [PerpusController::class, 'auth'])->name('login.auth');

Route::get('/userdata', [PerpusController::class, 'userdata'])->name('userdata');

Route::get('/book', [PerpusController::class, 'book'])->name('book');
Route::post('/book/category', [PerpusController::class, 'InputCategory'])->name('input.category');
Route::get('/editcategory/{id}', [PerpusController::class, 'editcategory'])->name('editcategory');
Route::patch('/updatecategory/{id}', [PerpusController::class, 'updatecateg'])->name('updatecateg');
Route::delete('/deletecateg/{id}', [PerpusController::class, 'destroycat'])->name('deletecateg');

Route::post('/createbook', [PerpusController::class, 'inputBook'])->name('input.book');
Route::get('/createbook', [PerpusController::class, 'createbook'])->name('createbook');
Route::get('/editbook/{id}', [PerpusController::class, 'editbook'])->name('editbook');
Route::patch('/updatebok/{id}', [PerpusController::class, 'updatebook'])->name('updatebook');
Route::delete('/deletebook/{id}', [PerpusController::class, 'destroy'])->name('deletebook');


Route::post('/borrow-book/{book}', [PerpusController::class, 'borrowBook'])->name('borrow.book');
Route::get('/dashboarduser', [PerpusController::class, 'dashboarduser'])->name('dashboarduser');

Route::post('/borrow-book/{book}', [PerpusController::class, 'borrowBook'])->name('borrow.book');
Route::post('/dashboarduser', [PerpusController::class, 'simpanreview'])->name('simpan.review');
Route::get('/mycollection', [PerpusController::class, 'myCollection'])->name('mycollection');
Route::post('/collect-book/{book}', [PerpusController::class, 'collectBook'])->name('collect.book');

Route::get('/borrowed', [PerpusController::class, 'borrowed'])->name('borrowed');
Route::get('/borrowed_admin', [PerpusController::class, 'borrowed_admin'])->name('borrowed_admin');


Route::post('/return-book/{bookId}', [PerpusController::class, 'returnBook'])->name('return.book');


Route::get('/error', [PerpusController::class, 'error'])->name('error');
Route::get('/logout', [PerpusController::class, 'logout'])->name('logout');


Route::get('borrows/export-borrow', [PerpusController::class, 'exportBorrows'])->name('borrow.export');
Route::get('user/export-pdf', [PerpusController::class, 'exportUser'])->name('user.export');
Route::get('category/export-pdf', [PerpusController::class, 'exportCate'])->name('category.export');
Route::get('books/export-pdf', [PerpusController::class, 'exportBooks'])->name('book.export');



