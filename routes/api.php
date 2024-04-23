<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForoApiController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\RolApiController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Feed\FeedController;




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('user', UserApiController::class)->middleware('auth:api');
Route::apiResource('rol', RolApiController::class);

Route::get('/users', [UserApiController::class, 'index'])->middleware('auth:api');



/*Route::delete('//{id}', [::class, 'destroy'])->middleware('auth:api');




*/


/*Route::get('/incapacidades', [IncapacidadesController::class, 'index'])->middleware('auth:api');*/

Route::get('/incapacidades', [IncapacidadesController::class, 'index'])->middleware('auth:api');
/*Route::get('incapacidades//all', [IncapacidadesController::class, 'indexall'])->middleware('auth:api');*/
Route::post('incapacidades//store', [IncapacidadesController::class, 'store'])->middleware('auth:api');

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


