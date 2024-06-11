<?php

namespace App\Http\Controllers\Incapacidades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str;
use App\Models\Incapacidades;
use App\Http\Requests\IncapacidadesRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;



class IncapacidadesController extends Controller
{

    public function index()
    {

        
        $incapacidades = Incapacidades::with('user')->latest()->get();
        return response([
            'incapacidades' => $incapacidades
        ], 200,[],JSON_NUMERIC_CHECK);


        
    }


    public function store(Request $request)
    {
        // Validar los datos entrantes
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'tipoincapacidadreportada' => 'required|string|max:50',
            'diasIncapacidad' => 'required|integer',
            'fechaInicioIncapacidad' => 'required|date',
            'entidadAfiliada' => 'required|string|max:50',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:20000'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $incapacidad = Incapacidades::create([
                'uuid' => (string) Str::orderedUuid(),
                "tipo_incapacidad_reportada" => $request->tipoincapacidadreportada,
                "dias_incapacidad" => $request->diasIncapacidad,
                "fecha_inicio_incapacidad" => $request->fechaInicioIncapacidad,
                "aplica_cobro" => $request->aplica_cobro,
                "entidad_afiliada" => $request->entidadAfiliada,
                "tipo_incapacidad" => $request->tipo_incapacidad,
                "user_id" => $request->user_id
            ]);

            $images = [];
            if($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = $image->getClientOriginalName();
                    $image->storeAs('incapacidad_folder/' . $incapacidad->id, $imageName);
                    $images[] = $imageName;
                }
                $incapacidad->update(['images' => json_encode($images)]);
            }

            return response()->json($incapacidad, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear la incapacidad: '.$e->getMessage());
            return response()->json(['error' => 'Error al crear la incapacidad'], 500);
        }
    }


    public function downloadFromDB($uuid)
{
    try {
        // Buscar la incapacidad por su UUID
        $incapacidad = Incapacidades::where('uuid', $uuid)->firstOrFail();
        
        // Obtener la ruta completa de la imagen
        $imagePath = storage_path("app/incapacidad_folder/{$incapacidad->id}/{$incapacidad->image}");
        
        // Verificar si la imagen existe
        if (!file_exists($imagePath)) {
            abort(404, 'La imagen no se encontró');
        }
        
        // Obtener el tipo MIME de la imagen
        $mimeType = mime_content_type($imagePath);
        
        // Devolver la imagen como una respuesta HTTP con el tipo MIME adecuado
        return response()->file($imagePath, ['Content-Type' => $mimeType]);
    } catch (\Exception $e) {
        // Manejar cualquier error que pueda ocurrir
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function update(Request $request, $id)
{
    $incapacidad = Incapacidades::find($id);
    if(!$incapacidad) {
        return response()->json(['message' => 'Incapacidad no encontrada'], 404);
    }
    
    // Solo actualiza los campos específicos si están presentes en la solicitud
    if ($request->has('aplica_cobro')) {
        $incapacidad->aplica_cobro = $request->aplica_cobro;
    }
    if ($request->has('tipo_incapacidad')) {
        $incapacidad->tipo_incapacidad = $request->tipo_incapacidad;
    }
    
    // Guarda los cambios en la base de datos
    $incapacidad->save();
    
    return response()->json($incapacidad);
}


public function downloadZip($uuid)
{
    try {
        // Buscar la incapacidad por su UUID
        $incapacidad = Incapacidades::where('uuid', $uuid)->firstOrFail();
        $images = json_decode($incapacidad->images);

        if (empty($images)) {
            return response()->json(['error' => 'No images found'], 404);
        }

        $zip = new \ZipArchive();
        $zipFileName = storage_path("app/incapacidad_folder/{$incapacidad->id}/incapacidad_{$uuid}.zip");

        if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($images as $image) {
                $filePath = storage_path("app/incapacidad_folder/{$incapacidad->id}/$image");
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

    
    public function destroy($id)
    {
        $incapacidad = Incapacidades::find($id);
        $incapacidad->delete();

        return response()->json(null, 204);
    }
    

}




