<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'user_id',
        'uuid',
        'images',];


        protected $casts = [
            'images' => 'array', // Cast JSON field to array
        ];
    
        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }



}
