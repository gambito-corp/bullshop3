<?php

use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UsersController;
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
    return redirect()->route('login');
});

Route::get('login', [CustomAuthController::class, 'showLoginForm'])->name('login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('test', [CategoryController::class, 'test'])->name('test');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

//    Rutas de Controlador de Clientes
    Route::get('clientes', [ClientController::class, 'index'])->name('client.index');
    Route::get('categorias', [CategoryController::class, 'index'])->name('category.index');
    Route::get('productos', [ProductController::class, 'index'])->name('product.index');
    Route::get('caja', [SaleController::class, 'index'])->name('sale.index');
    Route::get('usuarios', [UsersController::class, 'index'])->name('user.index');
});


