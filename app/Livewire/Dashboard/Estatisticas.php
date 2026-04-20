<?php

namespace App\Livewire\Dashboard;

use App\Models\Acompanhamento;
use App\Models\Assistida;
use App\Models\Agressor;
use App\Models\Visita;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Estatisticas extends Component
{
    public $periodo = 'anual';
    public $dataReferencia;

    public function mount()
    {
        $this->dataReferencia = Carbon::today()->format('Y-m-d');
    }

    public function voltarTempo()
    {
        $data = Carbon::parse($this->dataReferencia);
        if ($this->periodo == 'diario') $data->subDay();
        elseif ($this->periodo == 'mensal') $data->subMonth();
        else $data->subYear();
        
        $this->dataReferencia = $data->format('Y-m-d');
        $this->updatedPeriodo();
    }

    public function avancarTempo()
    {
        $data = Carbon::parse($this->dataReferencia);
        if ($this->periodo == 'diario') $data->addDay();
        elseif ($this->periodo == 'mensal') $data->addMonth();
        else $data->addYear();
        
        // Block advancing beyond today
        if ($data->greaterThan(Carbon::today())) {
            return;
        }

        $this->dataReferencia = $data->format('Y-m-d');
        $this->updatedPeriodo();
    }

    public function irParaHoje()
    {
        $this->dataReferencia = Carbon::today()->format('Y-m-d');
        $this->updatedPeriodo();
    }

    public function render()
    {
        return view('livewire.dashboard.estatisticas', [
            'kpis' => $this->carregarKPIs(),
            'chartData' => json_encode($this->carregarChartData(), JSON_NUMERIC_CHECK),
            'labelTempo' => $this->formatarDataLabel()
        ]);
    }

    public function updatedPeriodo()
    {
        $this->dispatch('periodo-changed', data: $this->carregarChartData());
    }

    private function formatarDataLabel()
    {
        $data = Carbon::parse($this->dataReferencia)->locale('pt_BR');
        if ($this->periodo == 'diario') return $data->translatedFormat('d \d\e F \d\e Y');
        if ($this->periodo == 'mensal') return ucfirst($data->translatedFormat('F \d\e Y'));
        return $data->format('Y');
    }

    private function carregarKPIs()
    {
        $dataBase = Carbon::parse($this->dataReferencia);
        
        $queryAcompanhamento = Acompanhamento::query();
        $queryAssistida = Assistida::query();
        $queryAgressor = Agressor::query();
        $queryVisita = Visita::query();

        if ($this->periodo == 'diario') {
            $queryAcompanhamento->whereDate('created_at', $dataBase);
            $queryAssistida->whereDate('created_at', $dataBase);
            $queryAgressor->whereDate('created_at', $dataBase);
            $queryVisita->whereDate('created_at', $dataBase);
        } elseif ($this->periodo == 'mensal') {
            $queryAcompanhamento->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year);
            $queryAssistida->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year);
            $queryAgressor->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year);
            $queryVisita->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year);
        } else {
            // Anual
            $queryAcompanhamento->whereYear('created_at', $dataBase->year);
            $queryAssistida->whereYear('created_at', $dataBase->year);
            $queryAgressor->whereYear('created_at', $dataBase->year);
            $queryVisita->whereYear('created_at', $dataBase->year);
        }

        return [
            'prontuarios' => $queryAcompanhamento->count(),
            'vitimas' => $queryAssistida->count(),
            'agressores' => $queryAgressor->count(),
            'visitas' => $queryVisita->count(),
        ];
    }

    private function carregarChartData()
    {
        $dataBase = Carbon::parse($this->dataReferencia);
        $labels = [];
        $dadosProntuarios = [];
        $dadosVitimas = [];
        $dadosVisitas = [];

        if ($this->periodo == 'anual') {
            $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            $prontuariosRaw = Acompanhamento::select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))->whereYear('created_at', $dataBase->year)->groupBy('mes')->pluck('total', 'mes')->toArray();
            $vitimasRaw = Assistida::select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))->whereYear('created_at', $dataBase->year)->groupBy('mes')->pluck('total', 'mes')->toArray();
            $visitasRaw = Visita::select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))->whereYear('created_at', $dataBase->year)->groupBy('mes')->pluck('total', 'mes')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $dadosProntuarios[] = $prontuariosRaw[$i] ?? 0;
                $dadosVitimas[] = $vitimasRaw[$i] ?? 0;
                $dadosVisitas[] = $visitasRaw[$i] ?? 0;
            }
        } elseif ($this->periodo == 'mensal') {
            $diasNoMes = $dataBase->daysInMonth;
            for ($i = 1; $i <= $diasNoMes; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
            }
            
            $prontuariosRaw = Acompanhamento::select(DB::raw('DAY(created_at) as dia'), DB::raw('count(*) as total'))->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year)->groupBy('dia')->pluck('total', 'dia')->toArray();
            $vitimasRaw = Assistida::select(DB::raw('DAY(created_at) as dia'), DB::raw('count(*) as total'))->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year)->groupBy('dia')->pluck('total', 'dia')->toArray();
            $visitasRaw = Visita::select(DB::raw('DAY(created_at) as dia'), DB::raw('count(*) as total'))->whereMonth('created_at', $dataBase->month)->whereYear('created_at', $dataBase->year)->groupBy('dia')->pluck('total', 'dia')->toArray();

            for ($i = 1; $i <= $diasNoMes; $i++) {
                $dadosProntuarios[] = $prontuariosRaw[$i] ?? 0;
                $dadosVitimas[] = $vitimasRaw[$i] ?? 0;
                $dadosVisitas[] = $visitasRaw[$i] ?? 0;
            }
        } elseif ($this->periodo == 'diario') {
            for ($i = 0; $i <= 23; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT).'h';
            }
            
            $prontuariosRaw = Acompanhamento::select(DB::raw('HOUR(created_at) as hora'), DB::raw('count(*) as total'))->whereDate('created_at', $dataBase)->groupBy('hora')->pluck('total', 'hora')->toArray();
            $vitimasRaw = Assistida::select(DB::raw('HOUR(created_at) as hora'), DB::raw('count(*) as total'))->whereDate('created_at', $dataBase)->groupBy('hora')->pluck('total', 'hora')->toArray();
            $visitasRaw = Visita::select(DB::raw('HOUR(created_at) as hora'), DB::raw('count(*) as total'))->whereDate('created_at', $dataBase)->groupBy('hora')->pluck('total', 'hora')->toArray();

            for ($i = 0; $i <= 23; $i++) {
                $dadosProntuarios[] = $prontuariosRaw[$i] ?? 0;
                $dadosVitimas[] = $vitimasRaw[$i] ?? 0;
                $dadosVisitas[] = $visitasRaw[$i] ?? 0;
            }
        }

        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Prontuários',
                    'data' => $dadosProntuarios,
                    'color' => '#a672b1' // Cor Primária
                ],
                [
                    'name' => 'Vítimas',
                    'data' => $dadosVitimas,
                    'color' => '#f97316' // Laranja
                ],
                [
                    'name' => 'Visitas/Rondas Realizadas',
                    'data' => $dadosVisitas,
                    'color' => '#3b82f6' // Azul de Ação Tática
                ]
            ]
        ];
    }
}
