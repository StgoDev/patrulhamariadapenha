<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Viatura extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_ativo' => 'boolean',
        ];
    }

    public function escalas()
    {
        return $this->hasMany(Escala::class);
    }
}
