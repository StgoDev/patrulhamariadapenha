<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'data_escala' => 'date',
        ];
    }

    public function viatura()
    {
        return $this->belongsTo(Viatura::class);
    }

    public function funcionarios()
    {
        return $this->belongsToMany(Funcionario::class, 'escala_funcionario')
                    ->withPivot('papel_patrulha')
                    ->withTimestamps();
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }
}
