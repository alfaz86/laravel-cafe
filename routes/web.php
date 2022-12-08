<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Models\Menu;
use Illuminate\Support\Facades\Route;

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
    $menus = Menu::all();
    return view('home', compact('menus'));
})->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/regist', [AuthController::class, 'regist'])->name('regist');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('menu')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('menus');
    Route::get('/table', [MenuController::class, 'datatableMenu'])->name('menu.table');
    Route::post('/create-modal', [MenuController::class, 'formcreate'])->name('menu.create.modal');
    Route::post('/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/edit-modal', [MenuController::class, 'formedit'])->name('menu.edit.modal');
    Route::put('/update-modal', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/delete', [MenuController::class, 'delete'])->name('menu.delete');

    Route::post('/add/cart', [MenuController::class, 'addCart'])->name('menu.add.cart');
    Route::get('/show/cart', [MenuController::class, 'showCart'])->name('menu.show.cart');
    Route::delete('/delete/cart', [MenuController::class, 'deleteCart'])->name('menu.delete.cart');
});

Route::prefix('order')->group(function () {
    Route::get('/',[OrderController::class,'index'])->name('orders');
    Route::post('/create',[OrderController::class,'create'])->name('order.create');
});
