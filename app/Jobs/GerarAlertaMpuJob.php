<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Visita;
use Illuminate\Support\Facades\Log;

class GerarAlertaMpuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $visita;

    /**
     * Create a new job instance.
     */
    public function __construct(Visita $visita)
    {
        $this->visita = $visita;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Gera PDF assíncrono (Placeholder)
        // 2. Dispara E-mail/API para Tribunal de Justiça / Polícia Civil
        
        Log::alert('ALERTA MÁXIMO DE QUEBRA DE MPU GERADO!', [
            'visita_id' => $this->visita->id,
            'prontuario' => $this->visita->acompanhamento->numero_processo,
            'data_agendada' => $this->visita->data_agendada,
            'patrulha_escala' => $this->visita->escala_id
        ]);
    }
}
