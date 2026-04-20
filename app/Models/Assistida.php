<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Assistida extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'endereco' => 'encrypted',
            'telefone' => 'encrypted',
            'data_nascimento' => 'date',
            'possui_deficiencia' => 'boolean',
        ];
    }

    public function acompanhamentos()
    {
        return $this->hasMany(Acompanhamento::class);
    }
}

