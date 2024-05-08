<?php

namespace App\Http\Controllers\Incapacidades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $incapacidad = Incapacidades::create([
            'uuid' => (string) Str::orderedUuid(),
            "dias_incapacidad" => $request->dias_incapacidad,
            "fecha_inicio_incapacidad" => $request->fecha_inicio_incapacidad,
            "aplica_cobro" => $request->aplica_cobro,
            "entidad_afiliada" => $request->entidad_afiliada,
             "tipo_incapacidad" => $request->tipo_incapacidad,
            "user_id" => $request->user_id
        
        ]);
        if($request->hasFile('image'))
        {
            $image = $request->file('image')->getClientOriginalName();
            $request->file('image')
                ->storeAs('incapacidad_folder/' . $incapacidad->id, $image);
            $incapacidad->update(['image' => $image]);
        }
        return response()->json($incapacidad, 201);
    }


   public function download($uuid)
{
    $incapacidad = Incapacidades::where('uuid', $uuid)->firstOrFail();
    $pathToFile = storage_path("app/public/incapacidad_folder/$uuid/" . $incapacidad->image);
    return response()->file($pathToFile);
}



    public function update(Request $request, $id)
    {
        $request->validate([
            'dias_incapacidad' => 'required|integer',
            'fecha_inicio_incapacidad' => 'required|date',
            'aplica_cobro' => 'required|boolean',
            'entidad_afiliada' => 'required|string|max:50',
            'tipo_incapacidad' => 'required|string|max:5',
        ]);

        $incapacidad = Incapacidades::findOrFail($id);
        $incapacidad->update($request->all());

        return response()->json($incapacidad, 200);
    }

    
    public function destroy($id)
    {
        $incapacidad = Incapacidades::find($id);
        $incapacidad->delete();

        return response()->json(null, 204);
    }
    




}




