<?php

namespace App\Exports;

use App\Models\Cesantias;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CesantiasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Cesantias::all();
    }

    public function headings(): array
    {
        return [
            'ID',  'Cedula','Nombre', 'Tipo de solicitud','', 'Estado', 'Imagen', 
        ];
    }
}