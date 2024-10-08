<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HorariosImport;
use App\Models\Horario;

class HorariosController extends Controller
{
    public function index()
    {
        $horarios = Horario::all();
        return response()->json($horarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $file = $request->file('import_file');

        if ($file) {
            Excel::import(new HorariosImport, $file);
            return response()->json(['success' => 'Archivo importado con éxito.'], 200);
        } else {
            return response()->json(['error' => 'No se ha seleccionado ningún archivo.'], 400);
        }
    }
}
