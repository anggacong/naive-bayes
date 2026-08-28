<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NaiveBayesModel extends Model
{
    protected $table = 'naive_bayes_model';

    protected $fillable = [
        'sumber',
        'tipe',
        'sentimen',
        'kata',
        'nilai_log',
    ];

    protected $casts = [
        'nilai_log' => 'float',
    ];

    public function scopeSumber(Builder $query, string $sumber): Builder
    {
        return $query->where('sumber', $sumber);
    }

    public function scopePrior(Builder $query): Builder
    {
        return $query->where('tipe', 'prior');
    }

    public function scopeLikelihood(Builder $query): Builder
    {
        return $query->where('tipe', 'likelihood');
    }
}
