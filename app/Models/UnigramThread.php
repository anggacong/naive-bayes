<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnigramThread extends Model
{
    protected $table = 'unigram_thread';

    protected $fillable = [
        'data_thread_id',
        'kata',
        'tf',
        'df',
        'idf',
        'tfidf',
        'sentimen',
    ];

    protected $casts = [
        'tf'    => 'float',
        'df'    => 'integer',
        'idf'   => 'float',
        'tfidf' => 'float',
    ];

    public function dataThread(): BelongsTo
    {
        return $this->belongsTo(DataThread::class, 'data_thread_id');
    }
}
