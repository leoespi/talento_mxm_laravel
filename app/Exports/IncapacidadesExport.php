<?php

namespace App\Exports;

use App\Models\Incapacidades;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncapacidadesExport implements FromCollection , WithHeadings
{
    public function collection()
    {
        return Incapacidades::all();
    }

    public function headings(): array
    {
        return [
            'ID', 'User_id', 'Dias de incapacidad', 'Fecha inicio incapacidad', 'Aplica cobro', 'Eps Afiliada', 'Tipo de incapacidad', 'Fecha de Creación','Fecha de Actualización'
        ];
    }
    

}
