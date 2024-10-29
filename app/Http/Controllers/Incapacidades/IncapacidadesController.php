<?php

namespace App\Http\Controllers\Incapacidades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Requests\IncapacidadesRequest;

//modelos
use App\Models\Incapacidades;
use App\Models\IncapacidadImage;
use App\Models\IncapacidadDocumentos;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class IncapacidadesController extends Controller
{
   
    public function indexAll()
{
    // Obtener todas las incapacidades con las relaciones de usuario e imágenes
    $incapacidades = Incapacidades::with('user', 'images')->get();

    // Iterar sobre cada incapacidad
    $incapacidades->each(function($incapacidad) {
        // Verificar si tiene imágenes antes de iterar
        if ($incapacidad->images) {
            $incapacidad->images->each(function($image) {
                $image->image_path = '/storage/' . $image->image_path; // Ajusta la ruta según tu almacenamiento
            });
        }

        if ($incapacidad->documentos) {
            $incapacidad->documentos->each(function($documento) {
                $documento->documentos = '/storage/' . $documento->documentos; // Ajusta la ruta según tu almacenamiento
            });
        }
    });
    return response([
        'incapacidades' => $incapacidades
    ], 200, [], JSON_NUMERIC_CHECK);
}

    
    

public function store(Request $request)
{
    try {
        // Validar los datos entrantes
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'tipoincapacidadreportada' => 'required|string|max:50',
            'diasIncapacidad' => 'required|integer',
            'fechaInicioIncapacidad' => 'required|date',
            'entidadAfiliada' => 'required|string|max:50',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $incapacidad = Incapacidades::create([
            'uuid' => (string) Str::orderedUuid(),
            "user_id" => $request->user_id,
            "tipo_incapacidad_reportada" => $request->tipoincapacidadreportada,
            "dias_incapacidad" => $request->diasIncapacidad,
            "fecha_inicio_incapacidad" => $request->fechaInicioIncapacidad,
            "aplica_cobro" => $request->aplica_cobro,
            "entidad_afiliada" => $request->entidadAfiliada,
            "tipo_incapacidad" => $request->tipo_incapacidad,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Guarda la imagen usando su nombre original
                $path = $image->storeAs('incapacidad_images', $image->getClientOriginalName(), 'public');
                $incapacidad->images()->create(['image_path' => $path]);
            }
        }

        // Manejar los documentos
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $documento) {
                // Guarda el documento usando su nombre original
                $path = $documento->storeAs('incapacidad_documentos', $documento->getClientOriginalName(), 'public');
                $incapacidad->documentos()->create(['documentos' => $path]);
            }
        }

        return response(['message' => 'success'], 201);
    } catch (Exception $e) {
        return response(['message' => 'error', 'error' => $e->getMessage()], 500);
    }
}


public function downloadDocument($id)
{
    try {
        // Buscar la incapacidad por su ID
        $incapacidad = Incapacidades::with('documentos')->findOrFail($id);
        
        // Crear un nuevo archivo ZIP
        $zip = new \ZipArchive();
        $zipFileName = storage_path("app/public/incapacidad_folder/{$incapacidad->id}/documentos_incapacidad_{$id}.zip");

        // Asegurarse de que el directorio existe
        $zipDir = dirname($zipFileName);
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0755, true);
        }

        if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
            // Verificar si hay documentos asociados
            if ($incapacidad->documentos->isEmpty()) {
                // Opción 1: Dejar el ZIP vacío y cerrarlo
                $zip->close();
                return response()->json(['message' => 'No hay documentos disponibles'], 200);
            }

            // Si hay documentos, agregarlos al ZIP
            foreach ($incapacidad->documentos as $documento) {
                $filePath = storage_path("app/public/{$documento->documentos}");
                if (file_exists($filePath)) {
                    // Usa el nombre original del archivo al añadir al ZIP
                    $zip->addFile($filePath, basename($filePath));
                } else {
                    \Log::error("File not found: $filePath");
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        // Descarga el archivo ZIP
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        \Log::error('Error al descargar los documentos: ' . $e->getMessage());
        return response()->json(['error' => 'Error al descargar los documentos'], 500);
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


public function downloadImages($id)
{
    try {
        // Buscar las imágenes asociadas a la incapacidad especificada
        $images = IncapacidadImage::where('incapacidades_id', $id)->get();

        // Crear un nuevo archivo ZIP
        $zip = new \ZipArchive();
        $zipFileName = storage_path("app/public/incapacidad_folder/{$id}/imagenes_incapacidad_{$id}.zip");

        // Asegurarse de que el directorio existe
        $zipDir = dirname($zipFileName);
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0755, true);
        }

        if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
            // Verificar si hay imágenes asociadas
            if ($images->isEmpty()) {
                $zip->close();
                return response()->json(['message' => 'No hay imágenes disponibles'], 200);
            }

            // Si hay imágenes, agregarlas al ZIP
            foreach ($images as $image) {
                $filePath = storage_path("app/public/{$image->image_path}");
                if (file_exists($filePath)) {
                    // Usa el nombre original del archivo al añadir al ZIP
                    $zip->addFile($filePath, basename($filePath));
                } else {
                    \Log::error("File not found: $filePath");
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        // Descarga el archivo ZIP
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        \Log::error('Error al descargar las imágenes: ' . $e->getMessage());
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



