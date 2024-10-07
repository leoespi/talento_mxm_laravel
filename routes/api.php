<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incapacidades\IncapacidadesController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\RolApiController;

use App\Http\Controllers\CesantiasController;
use App\Http\Controllers\ReferidosController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelIncapacidadesController;
use App\Http\Controllers\ExcelCesantiasController;
use App\Http\Controllers\FeedController;


// Rutas sin middleware
Route::post('password/forgot', [UserApiController::class, 'sendResetPin']);
Route::post('password/reset', [UserApiController::class, 'resetPasswordWithPin']);
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('registeradmin', [AuthenticationController::class, 'registerAdmin']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::get('/test', function () {
    return response(['message' => 'Api is working'], 200);
});
Route::get('/Excel', function () {
    return view('Excel');
});

// Rutas con middleware 'auth:api'
Route::middleware('auth:api')->group(function () {

    //endpoints Usuarios
    Route::put('/updateUser', [UserApiController::class, 'update']);
    Route::apiResource('user', UserApiController::class);
    Route::get('/users', [UserApiController::class, 'index']);
    Route::post('/users/{id}/activate', [UserApiController::class, 'activate']);
    Route::post('/users/{id}/deactivate', [UserApiController::class, 'deactivate']);
    Route::get('/export-users', [ExcelController::class, 'exportUsers'])->name('export-users');
    
    // EndPoints Incapacidades
    Route::apiResource('incapacidades', IncapacidadesController::class);
    Route::get('incapacidades/{uuid}/downloadFromDB', [IncapacidadesController::class, 'downloadFromDB'])->name('incapacidades.downloadFromDB');
    Route::get('/export-incapacidades', [ExcelIncapacidadesController::class, 'exportIncapacidades'])->name('export-incapacidades');
    Route::get('incapacidades/download-zip/{uuid}', [IncapacidadesController::class, 'downloadZip']);


    // EndPoints Cesantias
    Route::apiResource('cesantias', CesantiasController::class);
    Route::get('cesantias/download-zip/{uuid}', [CesantiasController::class, 'downloadZip']);
    Route::get('/export-cesantias/{year}', [ExcelCesantiasController::class, 'exportCesantias'])->name('export-cesantias');
    Route::put('/cesantias/{id}/authorize', [CesantiasController::class, 'authorizeCesantia']);
    Route::get('authorizedCesantia', [CesantiasController::class, 'indexCesantiasAutorizadas']);
    Route::get('authorizedCesantia/download-zip/{uuid}', [CesantiasController::class, 'downloadZipAutorized']);
    Route::post('cesantias/deny/{id}', [CesantiasController::class, 'DenyCesantia']);
    Route::post('/cesantias/denyadmin/{id}', [CesantiasController::class, 'DenyAuthorizedCesantia']);
    Route::post('cesantias/aprobar/{id}', [CesantiasController::class, 'AcceptCesantia']);


    Route::get('cesantias/{uuid}/images-size', [CesantiasController::class, 'calculateImagesSizeInMB']);

    
    // EndPoints Referidos
    Route::apiResource('referidos', ReferidosController::class);
    Route::get('referidos/download/{id}', [ReferidosController::class, 'downloadDocumento']);

    // EndPoints Feed
    Route::apiResource('feeds', FeedController::class);
    Route::post('feeds', [FeedController::class, 'store']);
    Route::get('feeds', [FeedController::class, 'index']);
    
    Route::get('/get/user', [UserApiController::class, 'indexUser']);
    Route::get('logout', [AuthController::class, "logout"]);
    Route::get("/perfil/ver", [PerfilController::class, 'verPerfil']);
});