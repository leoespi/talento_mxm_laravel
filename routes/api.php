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


Route::put('/updateUser', [UserApiController::class, 'update'])->middleware('auth:api');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('user', UserApiController::class)->middleware('auth:api');
Route::apiResource('rol', RolApiController::class);
Route::get('/users', [UserApiController::class, 'index'])->middleware('auth:api');
Route::get('/export-users', [ExcelController::class, 'exportUsers'])->name('export-users')->middleware('auth:api');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//EndPoints Incapacidades

Route::apiResource('incapacidades', IncapacidadesController::class)->middleware('auth:api');
Route::get('incapacidades/{uuid}/downloadFromDB', [IncapacidadesController::class, 'downloadFromDB'])->name('incapacidades.downloadFromDB')->middleware('auth:api');
Route::get('/export-incapacidades', [ExcelIncapacidadesController::class, 'exportIncapacidades'])->name('export-incapacidades')->middleware('auth:api');
Route::get('incapacidades/download-zip/{uuid}', [IncapacidadesController::class, 'downloadZip'])->middleware('auth:api');




//cesantias
Route::get('/cesantias/{uuid}/images-size', [CesantiasController::class, 'calculateImagesSizeInMB']); //calcular peso imagenes
Route::apiResource('cesantias', CesantiasController::class)->middleware('auth:api')->middleware('auth:api');
Route::get('cesantias/download-zip/{uuid}', [CesantiasController::class, 'downloadZip'])->middleware('auth:api');//Descargar cesantias en formato zip
Route::get('/export-cesantias/{year}', [ExcelCesantiasController::class, 'exportCesantias'])->name('export-cesantias');//Exportar cesantias excell

//cesantias autorizadas
Route::put('/cesantias/{id}/authorize', [CesantiasController::class, 'authorizeCesantia'])->middleware('auth:api');//Autorizar cesantias
Route::get('authorizedCesantia' , [CesantiasController::class,'indexCesantiasAutorizadas' ])->middleware('auth:api');//Ver cesantias autorizadas
Route::get('authorizedCesantia/download-zip/{uuid}', [CesantiasController::class, 'downloadZipAutorized'])->middleware('auth:api');

//cesantias denegadas
Route::put('/cesantias/{id}/deny', [CesantiasController::class, 'denyCesantia'])->middleware('auth:api');//denegar cesantias

//Cesantias Denegadas admin 
Route::post('cesantias/deny/{id}', [CesantiasController::class, 'DenyCesantia'])->middleware('auth:api');
//Denegar cesantias superadmin
Route::post('/cesantias/denyadmin/{id}', [CesantiasController::class, 'DenyAuthorizedCesantia'])->middleware('auth:api');
//cesantias aprobadas
Route::post('cesantias/aprobar/{id}', [CesantiasController::class, 'AcceptCesantia'])->middleware('auth:api');




//Endpoints Referidos
Route::apiResource('referidos', ReferidosController::class)->middleware('auth:api');
Route::get('referidos/download/{id}', [ReferidosController::class, 'downloadDocumento'])->middleware('auth:api');

//Endpoints Feed
Route::apiResource('feeds', FeedController::class);
Route::post('feeds', [FeedController::class, 'store']);




Route::get('/test', function () {
    return response([
        'message' => 'Api is working'
    ], 200);
});


Route::post('register', [AuthenticationController::class, 'register']);

Route::post('login', [AuthenticationController::class, 'login']);

Route::get('/get/user', [UserApiController::class, 'indexUser'])->middleware('auth:api');
Route::put('/updateUser', [UserApiController::class, 'update'])->middleware('auth:api');

Route::get("/perfil/ver", [PerfilController::class,'verPerfil']);

Route::get('logout', [AuthController::class, "logout"])->middleware('auth:api');
   
Route::get('/Excel', function () {
    return view('Excel');
});







