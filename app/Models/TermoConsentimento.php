<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TermoConsentimento extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'termos_consentimento';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'aceitou_programa' => 'boolean',
            'data_assinatura' => 'date',
        ];
    }

    public function acompanhamento()
    {
        return $this->belongsTo(Acompanhamento::class);
    }
}
