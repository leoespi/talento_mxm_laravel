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

use Illuminate\Support\Facades\Mail;
use App\Mail\CesantiaAprobada;
use App\Mail\CesantiaDenegada;



use ZipArchive;

use Illuminate\Database\Eloquent\Relations\HasMany;




class CesantiasController extends Controller
{
    


    /**
     * 
     * CESANTIAS
     * 
     * */ 

    public function index()
    {
        $cesantias = Cesantias::with('user')->latest()->get();
        return response([
            'cesantias' => $cesantias
        ], 200,[],JSON_NUMERIC_CHECK);
    }


    //Almacenar Cesantias
    public function store(Request $request)
    {
        Log::info('Datos recibidos en la solicitud:', $request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:15000'
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



    //Descargar Cesantias (creo que debo de quitar eso pq no se usa )
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


    
    //Descargar Cesantias en ZIP 
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


    //Eliminar cesantias (No se usa pero esta )
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
            // Encontrar la cesantía por su ID
            $cesantia = Cesantias::find($id);
    
            // Verificar si la cesantía existe
            if (!$cesantia) {
                return response()->json(['error' => 'Cesantia no encontrada'], 404);
            }
    
            // Verificar si la cesantía está en estado 'Denegada'
            if ($cesantia->estado === 'Denegada') {
                return response()->json(['error' => 'La cesantía no puede ser autorizada porque está denegada'], 422);
            }
    
            // Verificar si la cesantía ya está autorizada
            $authorizedCesantia = CesantiasAutorizadas::where('uuid', $cesantia->uuid)
                                    ->where('estado', 'Autorizada')
                                    ->exists();
    
            // Si la cesantía ya está autorizada, no se puede autorizar nuevamente
            if ($authorizedCesantia) {
                return response()->json(['error' => 'La cesantía ya ha sido autorizada previamente'], 422);
            }
    
            // Crear la cesantía autorizada
            $authorizedCesantia = CesantiasAutorizadas::create([
                'user_id' => $cesantia->user_id,
                'tipo_cesantia_reportada' => $cesantia->tipo_cesantia_reportada,
                'estado' => 'Autorizada',
                'uuid' => $cesantia->uuid,
                'images' => $cesantia->images,
            ]);
    
            // Actualizar el estado de la cesantía original
            $cesantia->estado = 'Autorizada';
            $cesantia->save();
    
            return response()->json($authorizedCesantia, 201);
        } catch (\Exception $e) {
            Log::error('Error al autorizar la cesantía: ' . $e->getMessage());
            return response()->json(['error' => 'Error al autorizar la cesantía. Detalles en el registro de errores.'], 500);
        }
    }
    


    //Denegar desde el Superadmin (Autorizada a denegada)
    public function DenyAuthorizedCesantia(Request $request, $id)
{

    try {
        // Encontrar la cesantía por su ID, incluyendo la relación con User
        $authorizedCesantia = CesantiasAutorizadas::with('cesantias','user')->find($id);

        // Verificar si la cesantía existe
        if (!$authorizedCesantia) {
            return response()->json(['error' => 'Cesantia no encontrada'], 404);
        }

        /*********************************/ 
        
        // Verificar si la cesantía está autorizada en la tabla Cesantias
        if ($authorizedCesantia->estado === 'Aprobada') {
            return response()->json(['error' => 'La cesantía está aprobada. No puede ser denegada'], 422);
        }

        // Verificar si la cesantía está denegada en la tabla Cesantias
        if ($authorizedCesantia->estado === 'Denegada') {
            return response()->json(['error' => 'La cesantía ya ha sido denegada previamente'], 422);
        }

        /**
         * Que cuando se deniegue la cesantia por parte del admin se cambie
         * el estado de las cesantias en revision a denegada
         */
        $cesantia = Cesantias::where('uuid', $authorizedCesantia->uuid)
        ->where('estado', 'Autorizada')
        ->first(); // Obtenemos el primer registro que cumple con los criterios

        if ($cesantia) {
        // Cambiamos el estado a 'Aprobada'
        $cesantia->estado = 'Denegada';
        $cesantia->save();
        }
        /****************************** */

        // Validar que la justificación esté presente
        $validator = Validator::make($request->all(), [
            'justificacion' => 'required|string'
        ]);

        // Si la validación falla, devolver errores de validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Actualizar el estado de la cesantía a Denegada
        $authorizedCesantia->estado = 'Denegada';
        $authorizedCesantia->save();

        // Enviar el correo electrónico de denegación con la justificación y el nombre del usuario
        Mail::to($authorizedCesantia->user->email)->send(new CesantiaDenegada(
            $request->justificacion,
            $authorizedCesantia->tipo_cesantia_reportada,
            $authorizedCesantia->user->name // Nombre del usuario
        ));

        // Retornar la vista de correo electrónico para mostrar la confirmación al usuario
        return response()->json([
            'message' => 'Cesantía denegada exitosamente',
            'justificacion' => $request->justificacion,
            'tipo_cesantia_reportada' => $authorizedCesantia->tipo_cesantia_reportada,
            'nombre_usuario' => $authorizedCesantia->user->name,
        ]);
        

    } catch (\Exception $e) {
        // Capturar cualquier excepción y registrarla en el log
        Log::error('Error al denegar la cesantía: ' . $e->getMessage());

        // Retornar un mensaje de error genérico con código 500
        return response()->json(['error' => 'Error al denegar la cesantía. Detalles en el registro de errores.'], 500);
    }
    
}



    /***
     * CESANTIAS APROBADAS 
     * 
     * cambie el estado a Aprobado en la tabla de autorizadas y en la de cesantias en revision
     */
     
    public function AcceptCesantia(Request $request, $id)
{
    try {
        // Encontrar la cesantía autorizada por su ID, incluyendo la relación con Cesantias y User
        $authorizedCesantia = CesantiasAutorizadas::with('cesantias', 'user')->find($id);

        // Verificar si la cesantía autorizada existe
        if (!$authorizedCesantia) {
            return response()->json(['error' => 'Cesantia Autorizada no encontrada'], 404);
        }


        // Verificar si la cesantía está en estado 'Denegada'
        if ($authorizedCesantia->estado === 'Denegada') {
            return response()->json(['error' => 'La cesantía no puede ser Aprobada porque está denegada'], 422);
        }

        // Verificar si la cesantía ya está aprobada
        if ($authorizedCesantia->estado === 'Aprobada') {
            return response()->json(['error' => 'La cesantía no puede ser Aprobada porque ya esta aprobada'], 422);
        }

        // Validar que la justificación esté presente
        $validator = Validator::make($request->all(), [
            'justificacion' => 'required|string'
        ]);

        // Si la validación falla, devolver errores de validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Actualizar el estado de la cesantía Autorizada a aprobada
        $authorizedCesantia->estado = 'Aprobada';
        $authorizedCesantia->save();

         /**
          * Que cuando se Apruebe la cesantia  se cambie 
          * el estado de las cesantias en revision a aprobada
          */
        $cesantia = Cesantias::where('uuid', $authorizedCesantia->uuid)
                    ->where('estado', 'Autorizada')
                    ->first(); // Obtenemos el primer registro que cumple con los criterios

        if ($cesantia) {
            // Cambiamos el estado a 'Aprobada'
            $cesantia->estado = 'Aprobada';
            $cesantia->save();
        }

        

        // Enviar el correo electrónico de aprobación con la justificación y el nombre del usuario
        Mail::to($authorizedCesantia->user->email)->send(new CesantiaAprobada(
            $request->justificacion,
            $authorizedCesantia->tipo_cesantia_reportada,
            $authorizedCesantia->user->name // Nombre del usuario
        ));

         // Retornar la vista de correo electrónico para mostrar la confirmación al usuario
         return response()->json([
            'message' => 'Cesantía denegada exitosamente',
            'justificacion' => $request->justificacion,
            'tipo_cesantia_reportada' => $authorizedCesantia->tipo_cesantia_reportada,
            'nombre_usuario' => $authorizedCesantia->user->name,
        ]);
        
        
    } catch (\Exception $e) {
        // Capturar cualquier excepción y registrarla en el log
        Log::error('Error al aprobar la cesantía: ' . $e->getMessage());

        // Retornar un mensaje de error genérico con código 500
        return response()->json(['error' => 'Error al aprobar la cesantía. Detalles en el registro de errores.'], 500);
    }
}
   
    //Eliminar cesantias autorizadas 
    public function destroy_Authorized($id)
    {
        $authorizedCesantia = CesantiasAutorizadas::find($id);
        $authorizedCesantia->delete();
        return response()->json(null, "Cesantia eliminada", 204);
    }


  
    /***
     * 
     * CESANTIAS DENEGADAS
     * 
     */

    public function DenyCesantia(Request $request, $id)
{
    try {
        // Encontrar la cesantía por su ID, incluyendo la relación con User
        $cesantia = Cesantias::with('user')->find($id);

        // Verificar si la cesantía existe
        if (!$cesantia) {
            return response()->json(['error' => 'Cesantia no encontrada'], 404);
        }

        // Verificar si la cesantía está autorizada en la tabla Cesantias
        if ($cesantia->estado === 'Autorizada') {
            return response()->json(['error' => 'La cesantía está autorizada. No puede ser denegada'], 422);
        }

        // Verificar si la cesantía está denegada en la tabla Cesantias
        if ($cesantia->estado === 'Denegada') {
            return response()->json(['error' => 'La cesantía ya ha sido denegada previamente'], 422);
        }


        // Verificar si la cesantía está denegada en la tabla Cesantias
        if ($cesantia->estado === 'Aprobada') {
            return response()->json(['error' => 'La cesantía está aprobada. No puede ser denegada'], 422);
        }

        // Validar que la justificación esté presente
        $validator = Validator::make($request->all(), [
            'justificacion' => 'required|string'
        ]);

        // Si la validación falla, devolver errores de validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Actualizar el estado de la cesantía a Denegada
        $cesantia->estado = 'Denegada';
        $cesantia->save();

        // Enviar el correo electrónico de denegación con la justificación y el nombre del usuario
        Mail::to($cesantia->user->email)->send(new CesantiaDenegada(
            $request->justificacion,
            $cesantia->tipo_cesantia_reportada,
            $cesantia->user->name // Nombre del usuario
        ));

        // Retornar una respuesta JSON de éxito
        return response()->json(['message' => 'Cesantía denegada exitosamente']);

    } catch (\Exception $e) {
        // Capturar cualquier excepción y registrarla en el log
        Log::error('Error al denegar la cesantía: ' . $e->getMessage());

        // Retornar un mensaje de error genérico con código 500
        return response()->json(['error' => 'Error al denegar la cesantía. Detalles en el registro de errores.'], 500);
    }
}


     //Calcular Peso de las imagenes de una cesantia en especifico 
     public function calculateImagesSizeInMB($uuid)
     {
         try {
             $cesantia = Cesantias::where('uuid', $uuid)->firstOrFail();
             $directory = storage_path("app/cesantias_folder/{$cesantia->id}");
             $totalSize = 0;
     
             if (file_exists($directory)) {
                 $files = scandir($directory);
     
                 foreach ($files as $file) {
                     if ($file !== '.' && $file !== '..') {
                         $filePath = $directory . DIRECTORY_SEPARATOR . $file;
                         $totalSize += filesize($filePath);
                     }
                 }
             }
     
             $sizeInMB = $totalSize / (1024 * 1024); // Convertir bytes a megabytes
     
             return $sizeInMB; // Devuelve el tamaño total en megabytes de las imágenes de la cesantía
         } catch (\Exception $e) {
             Log::error('Error al calcular el tamaño de las imágenes: ' . $e->getMessage());
             return -1; // Retorna un valor indicativo de error o no encontrado
         }
     }







}
