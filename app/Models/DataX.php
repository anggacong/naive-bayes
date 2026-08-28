<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataX extends Model
{
    protected $table = 'data_x';

    protected $fillable = [
        'username',
        'teks_asli',
        'tanggal',
        'sentimen',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function preprocessing(): HasOne
    {
        return $this->hasOne(PreprocessingX::class, 'data_x_id');
    }

    public function unigram(): HasMany
    {
        return $this->hasMany(UnigramX::class, 'data_x_id');
    }
}
