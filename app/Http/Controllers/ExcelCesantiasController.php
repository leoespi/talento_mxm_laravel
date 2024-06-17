<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CesantiasExport;

class ExcelCesantiasController extends Controller
{
    public function exportCesantias()
    {
        return Excel::download(new CesantiasExport, 'cesantias.xlsx');
    }

    public function headings(): array
    {
        return [
            'ID',  'Cedula','Nombre', 'Tipo de solicitud','', 'Estado', 'Imagen', 
        ];
    }
}
