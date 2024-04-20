<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/users/trashed', [UserController::class, 'trashedUsers'])->name('users.trashed');
Route::post('/users/{id}/restore', [UserController::class, 'restoreUser'])->name('users.restore');
Route::delete('/users/{id}/delete', [UserController::class, 'deleteUser'])->name('users.delete');


Route::get('/books/trashed', [BookController::class, 'trashedBooks'])->name('books.trashed');
Route::post('/books/{id}/restore', [BookController::class, 'restoreBook'])->name('books.restore');
Route::delete('/books/{id}/delete', [BookController::class, 'deleteBook'])->name('books.delete');

Route::resources([
    'books' => BookController::class,
    'roles' => RoleController::class,
    'users' => UserController::class,
]);


