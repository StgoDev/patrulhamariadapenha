<?php

namespace App\Services;

use App\Models\Visita;
use App\Models\Escala;
use App\Models\Acompanhamento;
use Illuminate\Support\Facades\DB;
use Exception;

class VisitaService
{
    /**
     * Agenda uma nova visita para um Prontuário (Acompanhamento) vinculando a uma Escala.
     */
    public function agendarVisita(Escala $escala, Acompanhamento $acompanhamento, array $dados)
    {
        if ($escala->status !== 'ATIVA') {
            throw new Exception('Não é possível agendar visitas em uma escala encerrada.');
        }

        if ($acompanhamento->trashed()) {
            throw new Exception('O Prontuário vinculado encontra-se arquivado/inativo.');
        }

        return DB::transaction(function () use ($escala, $acompanhamento, $dados) {
            return Visita::create([
                'escala_id' => $escala->id,
                'acompanhamento_id' => $acompanhamento->id,
                'data_agendada' => $dados['data_agendada'] ?? now(),
                'status_visita' => 'agendada',
                'tipo_monitoramento' => $dados['tipo_monitoramento'] ?? 'Preventivo',
            ]);
        });
    }

    /**
     * Patrulha inicia o preenchimento/deslocamento.
     */
    public function iniciarDeslocamento(Visita $visita)
    {
        if ($visita->status_visita !== 'agendada') {
            throw new Exception('A visita já foi iniciada ou finalizada.');
        }

        $visita->update(['status_visita' => 'em_deslocamento']);
        
        return $visita;
    }

    /**
     * Patrulha finaliza a visita e preenche o relatório.
     */
    public function registrarRetornoPatrulha(Visita $visita, array $relatorio)
    {
        if ($visita->status_visita === 'realizada') {
            throw new Exception('Esta visita já teve seu relatório fechado e assinado.');
        }

        DB::transaction(function () use ($visita, $relatorio) {
            $visita->update([
                'status_visita' => 'realizada',
                'tipo_monitoramento' => $relatorio['tipo_monitoramento'] ?? null,
                'resumo_atendimento' => $relatorio['resumo_atendimento'],
                'avaliacao_equipe' => $relatorio['avaliacao_equipe'] ?? null,
                'percepcao_assistida' => $relatorio['percepcao_assistida'] ?? null,
                'descumprimento_mpu' => $relatorio['descumprimento_mpu'] ?? false,
            ]);

            // Regra de Negócio: Se houve quebra da MPU, o Prontuário DEVE gritar 'Extremo'
            if (isset($relatorio['descumprimento_mpu']) && $relatorio['descumprimento_mpu'] === true) {
                
                $visita->acompanhamento->update([
                    'nivel_risco_fonar' => 'EXTREMO'
                ]);

                // Dispatch Job de Geração de PDF e Envio Institucional (Background)
                \App\Jobs\GerarAlertaMpuJob::dispatch($visita);
            }
        });

        return $visita;
    }
}
