<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'data_agendada' => 'datetime',
            'visto_comandante' => 'boolean',
            'resumo_atendimento' => 'encrypted',
            'avaliacao_equipe' => 'encrypted',
            'percepcao_assistida' => 'encrypted',
            'descumprimento_mpu' => 'boolean'
        ];
    }

    public function acompanhamento()
    {
        return $this->belongsTo(Acompanhamento::class);
    }
    
    public function escala()
    {
        return $this->belongsTo(Escala::class);
    }
}
