<?php

namespace App\Http\Controllers;

use App\Imports\CategoriasImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class CategoriaImportController extends Controller
{
    /**
     * Importar el archivo Excel con las categorías.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function importar(Request $request)
    {
        // Validar que el archivo esté presente
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        // Procesar la importación del archivo Excel
        Excel::import(new CategoriasImport, $request->file('archivo'));

        return response()->json(['success' => 'Archivo importado con éxito.'], 200);
       
    }
}
