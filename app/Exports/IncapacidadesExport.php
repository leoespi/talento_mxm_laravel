<?php

namespace App\Exports;

use App\Models\Incapacidades;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncapacidadesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Incapacidades::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Nombre del Usuario','Cedula', 'Dias de incapacidad', 'Fecha inicio incapacidad', 'Aplica cobro', 'Eps Afiliada', 'Tipo de incapacidad','tipo_incapacidad_reportada','image', 'Fecha de Creación', 'Fecha de Actualización'
        ];
    }

    public function map($incapacidad): array
    {
        return [
            $incapacidad->id,
            $incapacidad->user->name, // Acceder al nombre del usuario
            $incapacidad->user->cedula,
            $incapacidad->dias_incapacidad,
            $incapacidad->fecha_inicio_incapacidad,
            $incapacidad->aplica_cobro ? 'Sí' : 'No', // Convertir a 'Sí' o 'No'
            $incapacidad->entidad_afiliada,
            $incapacidad->tipo_incapacidad,
            $incapacidad->tipo_incapacidad_reportada,
            $incapacidad->image,
            $incapacidad->created_at,
            $incapacidad->updated_at,
        ];
    }
}
