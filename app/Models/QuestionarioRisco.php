<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class QuestionarioRisco extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'questionarios_risco';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'respostas_brutas' => 'array',
            'data_aplicacao' => 'date',
            'alerta_arma_fogo' => 'boolean',
            'alerta_risco_feminicidio' => 'boolean',
        ];
    }

    public function acompanhamento()
    {
        return $this->belongsTo(Acompanhamento::class);
    }
}
