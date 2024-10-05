<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

         // Obtener la fecha y hora actual
         $now = Carbon::now();

        $userData = [
            'name' => $request->name,
            'cedula' => $request->cedula,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => 2,
            'is_active' => false,
        ];

        $user = User::create($userData);
        $token = $user->createToken('talento_mxm_laravel');
        $accessToken = $token->accessToken;
        $token->expires_at = Carbon::now()->addHours(10);
        $token->save();

        return response([
            'user' => $user,
            'token' => $accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
        ], 201);
    }



    public function registerAdmin(RegisterRequest $request)
    {
        $request->validated();
        $rol_id = $request->rol_id;

        $userData = [
            'name' => $request->name,
            'cedula' => $request->cedula,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $rol_id,
            'is_active' => false,
        ];

        $user = User::create($userData);
        $token = $user->createToken('talento_mxm_laravel');

    // Acceder al token de texto plano
    $accessToken = $token->accessToken;

        return response([
            'user' => $user,
            'token' => $accessToken
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        // Buscar al usuario por cédula
        $user = User::where('cedula', $request->cedula)->first();

        // Verificar si el usuario existe, está activo y si la contraseña es correcta
        if (!$user || !$user->is_active || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Usuario no autorizado o  Inactivo'
            ], 401);
        }

        // Crear el token de acceso y obtener el token de texto plano
        $token = $user->createToken('talento_mxm_laravel');

        // Acceder al token de texto plano
        $accessToken = $token->accessToken;

        return response([
            'user' => $user,
            'token' => $accessToken
        ], 200);
    }


}
