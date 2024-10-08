<?php

namespace App\Imports;

use App\Models\Horario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class HorariosImport implements ToModel, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Horario([
            'cedula' => $row[0],
            'lunes'=> $row[1],
            'martes'=> $row[2],
            'miercoles'=> $row[3],
            'jueves'=> $row[4],
            'viernes'=> $row[5],
            'sabado'=> $row[6],
            'domingo'=> $row[7],
            
        ]);
    }




    public function chunkSize(): int
    {
        return 100; // Ajusta según lo necesites
    }
}
