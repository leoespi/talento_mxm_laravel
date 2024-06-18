<?php
namespace App\Exports;

use App\Models\Cesantias;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CesantiasExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        return Cesantias::whereYear('created_at', $this->year)->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Cedula', 'Nombre', 'Tipo de solicitud',
        ];
    }
}
