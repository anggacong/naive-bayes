<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataThread extends Model
{
    protected $table = 'data_thread';

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
        return $this->hasOne(PreprocessingThread::class, 'data_thread_id');
    }

    public function unigram(): HasMany
    {
        return $this->hasMany(UnigramThread::class, 'data_thread_id');
    }
}
