<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perfil;
use App\Service\FuncionesService;


use Illuminate\Http\Request;

class PerfilController extends Controller
{

    private $service;
    public function __construct(FuncionesService $service){
        $this->service = $service;
    }


    public function store(Request $request)
{
    $user = auth()->user();

    $perfil = $user->perfil()->create([
        'name' => $request->name,
        'cedula' => $request->cedula,
        'email' => $request->email,
        'rol_id' => $request->rol_id,
    ]);

    return response()->json($perfil, 201);
}


public function verPerfil()
{
    $user_id = $this->service->obtenerIdUserAutenticado();
        if (!$user_id) {
            return response()->json(["error" => "Usuario no autorizado"],403);
        }
        $perfiles = User::find($user_id)->perfil;
        if ($perfiles) {
            return response()->json($perfiles, 200);
        }
        return response()->json(["error"=>"Perfil no encontrado"], 404);
}



    public function update(Request $request)
    {
        $user = auth()->user();

        $perfil = $user->perfil;

        if (!$perfil) {
            return response()->json(["error" => "Perfil no encontrado"], 404);
        }

        $perfil->name = $request->name;
        $perfil->cedula = $request->cedula;
        $perfil->email = $request->email;
        $perfil->save();


        return response()->json($perfil, 200);
    }


    public function destroy()
    {
        $user = auth()->user();

        $perfil = $user->perfil;

        if ($perfil) {
            $perfil->delete();
            return response()->json("Perfil eliminado", 200);
        }

        return response()->json(["error" => "Perfil no encontrado"], 404);
    }

}
