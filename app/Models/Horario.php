<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',

        'lunes2',
        'martes2',
        'miercoles2',
        'jueves2',
        'viernes2',
        'sabado2',
        'domingo2',
        'fecha_inicio',
        'fecha_fin',
    ];
}

