<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NaiveBayesHasil extends Model
{
    protected $table = 'naive_bayes_hasil';

    protected $fillable = [
        'sumber',
        'data_id',
        'split',
        'sentimen_asli',
        'sentimen_prediksi',
        'benar',
    ];

    protected $casts = [
        'benar'   => 'boolean',
        'data_id' => 'integer',
    ];

    public function scopeSumber(Builder $query, string $sumber): Builder
    {
        return $query->where('sumber', $sumber);
    }

    public function scopeTesting(Builder $query): Builder
    {
        return $query->where('split', 'test');
    }

    public function scopeTraining(Builder $query): Builder
    {
        return $query->where('split', 'train');
    }
}
