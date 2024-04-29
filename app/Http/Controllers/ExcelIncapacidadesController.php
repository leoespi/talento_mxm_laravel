<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncapacidadesExport;


class ExcelIncapacidadesController extends Controller
{
    public function exportIncapacidades()
    {
        return Excel::download(new IncapacidadesExport, 'Incapacidades.xlsx');
    }

    public function headings(): array
    {
        return [
            'ID', 'User_id', 'Dias de incapacidad', 'Fecha inicio incapacidad', 'Aplica cobro', 'Eps Afiliada', 'Tipo de incapacidad', 'Fecha de Creación','Fecha de Actualización'
        ];
    }
}
