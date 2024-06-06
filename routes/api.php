<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incapacidades\IncapacidadesController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\RolApiController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelIncapacidadesController;





Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('user', UserApiController::class)->middleware('auth:api');
Route::apiResource('rol', RolApiController::class);

Route::get('/users', [UserApiController::class, 'index'])->middleware('auth:api');



Route::apiResource('incapacidades', IncapacidadesController::class)->middleware('auth:api');
Route::get('incapacidades/{uuid}/downloadFromDB', [IncapacidadesController::class, 'downloadFromDB'])->name('incapacidades.downloadFromDB');





Route::get('/test', function () {
    return response([
        'message' => 'Api is working'
    ], 200);
});

Route::put('/updateUser', [UserApiController::class, 'update'])->middleware('auth:api');

Route::post('register', [AuthenticationController::class, 'register']);

Route::post('login', [AuthenticationController::class, 'login']);

Route::get('/get/user', [UserApiController::class, 'indexUser'])->middleware('auth:api');
Route::put('/updateUser', [UserApiController::class, 'update'])->middleware('auth:api');

Route::get("/perfil/ver", [PerfilController::class,'verPerfil']);

Route::get('logout', [AuthController::class, "logout"])->middleware('auth:api');
   
Route::get('/Excel', function () {
    return view('Excel');
});



Route::get('/export-incapacidades', [ExcelIncapacidadesController::class, 'exportIncapacidades'])->name('export-incapacidades');

Route::get('/export-users', [ExcelController::class, 'exportUsers'])->name('export-users')->middleware('auth:api');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


