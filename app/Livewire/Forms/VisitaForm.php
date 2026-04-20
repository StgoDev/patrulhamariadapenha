<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class VisitaForm extends Form
{
    #[Validate('required|in:visita,ronda,contato_telefonico', message: 'Tipo de monitoramento inválido.')]
    public $tipo_monitoramento = '';

    // Utilizando regex para proibir tags HTML nativamente (sanitização de segurança contra XSS)
    #[Validate('required|min:10', message: 'O resumo tático deve conter no mínimo 10 caracteres.')]
    #[Validate('regex:/^[^<>]+$/', message: 'Caracteres inválidos (Tags HTML/Scripts) bloqueados na auditoria.')]
    public $resumo_atendimento = '';

    #[Validate('nullable|string|regex:/^[^<>]*$/', message: 'Caracteres inválidos (Tags HTML/Scripts) bloqueados.')]
    public $avaliacao_equipe = '';

    #[Validate('nullable|string|regex:/^[^<>]*$/', message: 'Caracteres inválidos (Tags HTML/Scripts) bloqueados.')]
    public $percepcao_assistida = '';

    #[Validate('boolean')]
    public $descumprimento_mpu = false;

    /**
     * Limpa explicitamente os dados que não deveriam ter chego
     */
    public function sanitize()
    {
        $this->resumo_atendimento = strip_tags($this->resumo_atendimento);
        $this->avaliacao_equipe = strip_tags($this->avaliacao_equipe);
        $this->percepcao_assistida = strip_tags($this->percepcao_assistida);
    }
}
