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

Route::get('/registeruser', [PerpusController::class, 'register'])->name('register');
Route::post('/registeruser', [PerpusController::class, 'InputUser'])->name('register.user');

Route::post('/login', [PerpusController::class, 'auth'])->name('login.auth');
Route::get('/login', [PerpusController::class, 'login'])->name('login');

Route::patch('/updatecategory/{id}', [PerpusController::class, 'updatecateg'])->name('updatecateg');
Route::delete('/deletecateg/{id}', [PerpusController::class, 'destroycat'])->name('deletecateg');
Route::post('/createbook', [PerpusController::class, 'inputBook'])->name('input.book');
Route::get('/createbook', [PerpusController::class, 'createbook'])->name('createbook');
Route::get('/editbook/{id}', [PerpusController::class, 'editbook'])->name('editbook');
Route::patch('/update/{id}', [PerpusController::class, 'updatebook'])->name('updatebook');
Route::delete('/delete/{id}', [PerpusController::class, 'destroy'])->name('delete');
