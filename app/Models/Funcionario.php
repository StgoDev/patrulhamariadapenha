<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionarios';
    protected $guarded = ['id'];
    
    // Configura os Casts explícitos para o legado
    protected $casts = [
        'sigilo' => 'boolean',
        'planejada' => 'boolean',
        'planejada_restricao_interna' => 'boolean',
        'gdi' => 'boolean',
        'gde' => 'boolean',
        'gde_certificado' => 'boolean',
        
        'data_nascimento' => 'date',
        'data_inclusao' => 'date',
        'nascimento' => 'date',
        'inclusao' => 'date',
        'promocao' => 'date',
        'cnh_validade' => 'date',
        
        'altura' => 'float',
        'peso' => 'float',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function escalas()
    {
        return $this->belongsToMany(Escala::class, 'escala_funcionario')
                    ->withPivot('papel_patrulha')
                    ->withTimestamps();
    }

    public function getPatenteAttribute()
    {
        $patentes = [
            1 => 'Cel PM', 2 => 'Ten Cel PM', 3 => 'Maj PM', 4 => 'Cap PM', 
            5 => '1º Ten PM', 6 => '2º Ten PM', 7 => 'Asp Of PM',
            8 => 'Sub Ten PM', 9 => '1º Sgt PM', 10 => '2º Sgt PM', 
            11 => '3º Sgt PM', 12 => 'Cb PM', 13 => 'Sd PM'
        ];
        return $patentes[$this->posto_graduacao] ?? 'Sd PM';
    }

    public function getNomeGuerraAttribute()
    {
        if (empty($this->nome) || $this->nome === '**') return 'NÃO INFORMADO';
        $partes = explode(' ', $this->nome);
        return count($partes) > 1 ? end($partes) : $this->nome;
    }
}
