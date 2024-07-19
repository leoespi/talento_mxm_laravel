<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publicacion extends Model
{
    use HasFactory;

    protected $table = 'publicaciones';

    protected $fillable = [

        'user_id',
        'titulo',
        'contenido',
        'imagen'
    ];

    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
