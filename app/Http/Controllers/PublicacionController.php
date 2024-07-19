<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;



class PublicacionController extends Controller
{
    
    public function index()
    {
        $publicacion = Publicacion::with('user')->latest()->get();
        return response([
            'publicacion' => $publicacion
        ], 200,[],JSON_NUMERIC_CHECK);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'titulo' => 'required|string',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        try {
            $publicacion = new Publicacion();
            $publicacion->user_id = $request->user_id; // Assign user_id here
            $publicacion->titulo = $request->titulo;
            $publicacion->contenido = $request->contenido;
    
            // Procesar la imagen si se ha subido
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $imageName = time() . '_' . $imagen->getClientOriginalName();
                $imagen->storeAs('publicaciones_folder', $imageName);
                $publicacion->imagen = $imageName;
            }
    
            $publicacion->save();
    
            return response()->json($publicacion, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear la publicación: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear la publicación. Detalles en el registro de errores.'], 500);
        }
    }
    
    public function update(Request $request, $id)
{
    try {
        $publicacion = Publicacion::find($id);

        if (!$publicacion) {
            return response()->json(['error' => 'La publicación no existe.'], 404);
        }

        // Actualiza los campos según estén presentes en la solicitud
        if ($request->has('titulo')) {
            $publicacion->titulo = $request->titulo;
        }
        if ($request->has('contenido')) {
            $publicacion->contenido = $request->contenido;
        }

        // Procesar la nueva imagen si se ha subido
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($publicacion->imagen) {
                Storage::delete('publicaciones_folder/' . $publicacion->imagen);
            }

            // Subir la nueva imagen
            $imagen = $request->file('imagen');
            $imageName = time() . '_' . $imagen->getClientOriginalName();
            $imagen->storeAs('publicaciones_folder', $imageName);
            $publicacion->imagen = $imageName;
        }

        $publicacion->save();

        return response()->json($publicacion, 200);
    } catch (\Exception $e) {
        Log::error('Error al actualizar la publicación: ' . $e->getMessage());
        return response()->json(['error' => 'Error al actualizar la publicación. Detalles en el registro de errores.'], 500);
    }
}

    
    


}
