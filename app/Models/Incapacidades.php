<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Incapacidades extends Model
{

    use HasFactory;


    protected $fillable = [
        
        'user_id',
        'dias_incapacidad',
        'fecha_inicio_incapacidad',
        'aplica_cobro',
        'entidad_afiliada',
        'tipo_incapacidad',
        
        
    ];


    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
