<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KamusNormalisasi extends Model
{
    protected $table = 'kamus_normalisasi';
    protected $fillable = ['slang', 'formal'];
}
