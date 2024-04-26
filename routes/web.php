<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;



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
Route::get('/Excel', function () {
    return view('Excel');
});


Route::get('/export-users', [ExcelController::class, 'exportUsers'])->name('export-users');

/*El miwdleware para acceder solamente con el token, solo los usuarios registrados puedan acceder al documento*/
/*Implementar para que solamente el admin pueda descargar este registro */
/*->middleware('auth:api') */


