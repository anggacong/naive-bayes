<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreprocessingX extends Model
{
    protected $table = 'preprocessing_x';

    protected $fillable = [
        'data_x_id',
        'hasil_cleaning',
        'hasil_case_folding',
        'hasil_tokenizing',
        'hasil_repeated_char',
        'hasil_normalisasi',
        'hasil_stopword',
        'hasil_stemming',
    ];

    protected $casts = [
        'hasil_tokenizing' => 'array',
    ];

    public function dataX(): BelongsTo
    {
        return $this->belongsTo(DataX::class, 'data_x_id');
    }
}
