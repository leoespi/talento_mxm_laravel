<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\IncapacidadesRequest;
use App\Models\Incapacidades;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncapacidadesController extends Controller
{

    public function index()
    {
        $incapacidades = Incapacidad::with('user')->latest()->get();
        return response([
            'incapacidades' => $incapacidades
        ], 200,[],JSON_NUMERIC_CHECK);

        
    }

    



    public function store(Request $request)
    {
        $request->validate([
            'dias_incapacidad' => 'required|integer',
            'fecha_inicio_incapacidad' => 'required|date',
            'aplica_cobro' => 'required|boolean',
            'entidad_afiliada' => 'required|string|max:50',
            'tipo_incapacidad' => 'required|string|max:50',
        ]);


        Incapacidad ::create([
            "dias_incapacidad" => $request->dias_incapacidad,
            "fecha_inicio_incapacidad" => $request->fecha_inicio_incapacidad,
            "aplica_cobro" => $request->aplica_cobro,
            "entidad_afiliada" => $request->entidad_afiliada,
            "tipo_incapacidad" => $request->tipo_incapacidad,
           
            "user_id" => $request->user_id
        ]);
        $incapacidad = Incapacidad::create($request->all());

        return response()->json($incapacidad, 201);
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

        $incapacidad = Incapacidad::findOrFail($id);
        $incapacidad->update($request->all());

        return response()->json($incapacidad, 200);
    }

    
    public function destroy($id)
    {
        $incapacidad = Incapacidad::findOrFail($id);
        $incapacidad->delete();

        return response()->json(null, 204);
    }
    




}
