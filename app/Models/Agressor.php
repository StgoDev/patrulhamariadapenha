<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Agressor extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'agressores';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'preso' => 'boolean',
            'possui_arma_fogo' => 'boolean',
        ];
    }

    public function acompanhamentos()
    {
        return $this->hasMany(Acompanhamento::class);
    }
}
