<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Acompanhamento extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_encerramento' => 'date',
            'data_notificacao' => 'date',
            'notificado' => 'boolean',
        ];
    }

    public function assistida()
    {
        return $this->belongsTo(Assistida::class);
    }

    public function agressor()
    {
        return $this->belongsTo(Agressor::class);
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    public function termoConsentimento()
    {
        return $this->hasOne(TermoConsentimento::class);
    }

    public function questionarioRisco()
    {
        return $this->hasOne(QuestionarioRisco::class);
    }
}
