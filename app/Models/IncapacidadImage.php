<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncapacidadImage extends Model
{
    protected $fillable = ['image'];

    public function incapacidad(): BelongsTo
    {
        return $this->belongsTo(Incapacidad::class);
    }
}
