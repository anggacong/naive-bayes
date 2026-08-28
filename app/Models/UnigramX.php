<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnigramX extends Model
{
    protected $table = 'unigram_x';

    protected $fillable = [
        'data_x_id',
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

    public function dataX(): BelongsTo
    {
        return $this->belongsTo(DataX::class, 'data_x_id');
    }
}
