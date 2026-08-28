<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreprocessingThread extends Model
{
    protected $table = 'preprocessing_thread';

    protected $fillable = [
        'data_thread_id',
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

    public function dataThread(): BelongsTo
    {
        return $this->belongsTo(DataThread::class, 'data_thread_id');
    }
}
