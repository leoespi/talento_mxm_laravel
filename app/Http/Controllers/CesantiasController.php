<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str;
use App\Models\Cesantias;
use App\Models\CesantiasAutorizadas;
use App\Models\CesantiasDenegadas;
use ZipArchive;

use Illuminate\Database\Eloquent\Relations\HasMany;




class CesantiasController extends Controller
{
    

    public function index()
    {
        $cesantias = Cesantias::with('user')->latest()->get();
        return response([
            'cesantias' => $cesantias
        ], 200,[],JSON_NUMERIC_CHECK);
    }



    public function store(Request $request)
    {
        Log::info('Datos recibidos en la solicitud:', $request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:20000'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $cesantias = Cesantias::create([
                'uuid' => (string) Str::orderedUuid(),
                'tipo_cesantia_reportada' => $request->tipocesantiareportada,
                'estado' => $request->estado,
                'user_id' => $request->user_id
            ]);

            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = $image->getClientOriginalName();
                    $image->storeAs('cesantias_folder/' . $cesantias->id, $imageName);
                    $images[] = $imageName;
                }
                $cesantias->update(['images' => json_encode($images)]);
            }

            return response()->json($cesantias, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear la cesantia: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear la cesantia. Detalles en el registro de errores.'], 500);
        }
    }

    public function downloadFromDB($uuid)
    {  
        try {
            $cesantias = Cesantias::where('uuid', $uuid)->firstOrFail();
            $imagePath = storage_path("app/cesantias_folder/{$cesantias->id}/{$cesantias->image}");

            if (!file_exists($imagePath)) {
                abort(404, 'La imagen no se encontró');
            }

            $mimeType = mime_content_type($imagePath);
            return response()->file($imagePath, ['Content-Type' => $mimeType]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    

    public function downloadZip($uuid)
    {
        try {
            $cesantias = Cesantias::where('uuid', $uuid)->firstOrFail();
            $images = json_decode($cesantias->images);

            if (empty($images)) {
                return response()->json(['error' => 'No images found'], 404);
            }

            $zip = new \ZipArchive();
            $zipFileName = storage_path("app/cesantias_folder/{$cesantias->id}/cesantias_{$uuid}.zip");

            if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
                foreach ($images as $image) {
                    $filePath = storage_path("app/cesantias_folder/{$cesantias->id}/$image");
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $image);
                    } else {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
                        Log::error("File not found: $filePath");
                    }
                }
                $zip->close();
            } else {
                return response()->json(['error' => 'Could not create ZIP file'], 500);
            }

            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error al descargar las imágenes: ' . $e->getMessage());
            return response()->json(['error' => 'Error al descargar las imágenes'], 500);
        }
    }


    
    public function update(Request $request, $id)
    {
        $cesantias = Cesantias::find($id);
        if(!$cesantias) {
            return response()->json(['message' => 'Cesantia no encontrada'], 404);
        }

        if ($request->has('estado')) {
            $cesantias->estado = $request->estado;
        }

        $cesantias->save();
        return response()->json($cesantias);
    }


    public function destroy($id)
    {
        $cesantias = Cesantias::find($id);
        $cesantias->delete();
        return response()->json(null, "Cesantia eliminada", 204);
    }




    //Funciones de  Cesantias Autorizadas
    /******************************************************************* */ 


    public function indexCesantiasAutorizadas()
    {
        $authorizedCesantia = CesantiasAutorizadas::with('user')->latest()->get();
        return response([
            'authorizedCesantia' => $authorizedCesantia
        ], 200,[],JSON_NUMERIC_CHECK);
    }

    public function authorizeCesantia($id)
    {
        try {
            $cesantia = Cesantias::find($id);
    
            if (!$cesantia) {
                return response()->json(['error' => 'Cesantia no encontrada'], 404);
            }
    
            // Crear la cesantía autorizada
            $authorizedCesantia = CesantiasAutorizadas::create([
                'user_id' => $cesantia->user_id,
                'tipo_cesantia_reportada' => $cesantia->tipo_cesantia_reportada,
                'estado' => $cesantia->estado,
                'uuid' => $cesantia->uuid,
                'images' => $cesantia->images,
            ]);
    
            // Actualizar el estado de la cesantía original
            $cesantia->estado = 'Autorizada';
            $cesantia->save();

            $authorizedCesantia->estado = 'Autorizada';
            $authorizedCesantia ->save();

    
            return response()->json($authorizedCesantia, 201);
        } catch (\Exception $e) {
            Log::error('Error al autorizar la cesantía: ' . $e->getMessage());
            return response()->json(['error' => 'Error al autorizar la cesantía. Detalles en el registro de errores.'], 500);
        }
    }
    

    public function downloadFromDB_Authorized($uuid)
    {  
        try {
            $authorizedCesantia = CesantiasAutorizadas::where('uuid', $uuid)->firstOrFail();
            $imagePath = storage_path("app/cesantias_folder/{$authorizedCesantia->id}/{$authorizedCesantia->image}");

            if (!file_exists($imagePath)) {
                abort(404, 'La imagen no se encontró');
            }

            $mimeType = mime_content_type($imagePath);
            return response()->file($imagePath, ['Content-Type' => $mimeType]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   

    public function destroy_Authorized($id)
    {
        $authorizedCesantia = CesantiasAutorizadas::find($id);
        $authorizedCesantia->delete();
        return response()->json(null, "Cesantia eliminada", 204);
    }




     //Funciones de  Cesantias Denegadas
    /******************************************************************* */ 


    public function indexCesantiasDenegadas()
    {
        $denyCesantia = CesantiasDenegadas::with('user')->latest()->get();
        return response([
            'denyCesantia' => $denyCesantia
        ], 200,[],JSON_NUMERIC_CHECK);
    }


    public function denyCesantia($id)
    {
        try {
            $cesantia = Cesantias::find($id);

            if (!$cesantia) {
                return response()->json(['error' => 'Cesantia no encontrada'], 404);
            }

            $denyCesantia = CesantiasDenegadas::create([
                'user_id' => $cesantia->user_id,
                'tipo_cesantia_reportada' => $cesantia->tipo_cesantia_reportada,
                'estado' => $cesantia->estado,
                'uuid' => $cesantia->uuid,
                'images' => $cesantia->images,
            ]);

            // Actualizar el estado de la cesantía original
            $cesantia->estado = 'Denegada';
            $cesantia->save();

        
            $denyCesantia->estado = 'Denegada';
            $denyCesantia ->save();





            
            return response()->json($denyCesantia, 201);
        } catch (\Exception $e) {
            Log::error('Error al autorizar la cesantía: ' . $e->getMessage());
            return response()->json(['error' => 'Error al autorizar la cesantía. Detalles en el registro de errores.'], 500);
        }
    }


    public function moveAuthorizedToDenied($id)
{
    try {
        // Buscar la cesantía autorizada por su ID
        $authorizedCesantia = CesantiasAutorizadas::find($id);

        if (!$authorizedCesantia) {
            return response()->json(['error' => 'Cesantía autorizada no encontrada'], 404);
        }

        // Crear la cesantía denegada basada en la información de la cesantía autorizada
        $deniedCesantia = CesantiasDenegadas::create([
            'user_id' => $authorizedCesantia->user_id,
            'tipo_cesantia_reportada' => $authorizedCesantia->tipo_cesantia_reportada,
            'estado' => 'Denegada', // Puedes establecer el estado directamente como 'Denegada'
            'uuid' => $authorizedCesantia->uuid,
            'images' => $authorizedCesantia->images,
        ]);

        // Eliminar la cesantía autorizada
        $authorizedCesantia->delete();

        // Actualizar el estado de la cesantía original (en caso de que se requiera)
        $originalCesantia = Cesantias::where('uuid', $authorizedCesantia->uuid)->first();
        if ($originalCesantia) {
            $originalCesantia->estado = 'Denegada';
            $originalCesantia->save();
        }

        return response()->json(['message' => 'Cesantía autorizada movida a denegada correctamente', 'deniedCesantia' => $deniedCesantia], 200);
    } catch (\Exception $e) {
        Log::error('Error al mover la cesantía autorizada a denegada: ' . $e->getMessage());
        return response()->json(['error' => 'Error al mover la cesantía autorizada a denegada. Detalles en el registro de errores.'], 500);
    }
}







    public function destroy_Deny($id)
    {
        $denyCesantia = CesantiasDenegadas::find($id);
        $denyCesantia->delete();
        return response()->json(null, "Cesantia eliminada", 204);
    }







}
