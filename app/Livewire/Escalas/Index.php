<?php

namespace App\Livewire\Escalas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Escala;
use App\Models\Viatura;
use App\Models\Funcionario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $isEditing = false;
    public $escalaId = null;
    
    // Modal Fields
    public $data_escala;
    public $inicio = '08:00';
    public $turno = '12';
    public $viatura_id = '';
    
    // Guarnição
    public $comandante_id = '';
    public $motorista_id = '';
    public $patrulheiro_id = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function rules()
    {
        return [
            'data_escala' => 'required|date',
            'inicio' => 'required|date_format:H:i',
            'turno' => 'required|in:6,8,12,18,24',
            'viatura_id' => 'required|exists:viaturas,id',
            'comandante_id' => 'required|exists:funcionarios,id',
            'motorista_id' => 'required|exists:funcionarios,id|different:comandante_id',
            'patrulheiro_id' => 'nullable|exists:funcionarios,id|different:comandante_id|different:motorista_id',
        ];
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['escalaId', 'viatura_id', 'comandante_id', 'motorista_id', 'patrulheiro_id']);
        $this->data_escala = Carbon::now()->format('Y-m-d');
        $this->inicio = '08:00';
        $this->turno = '12';
        $this->isEditing = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $escala = Escala::with('funcionarios')->findOrFail($id);
        
        $this->escalaId = $escala->id;
        $this->data_escala = $escala->data_escala;
        if ($this->data_escala instanceof Carbon) {
            $this->data_escala = $this->data_escala->format('Y-m-d');
        }
        $this->inicio = $escala->inicio ? Carbon::parse($escala->inicio)->format('H:i') : '08:00';
        $this->turno = $escala->turno;
        $this->viatura_id = $escala->viatura_id;

        // Reset
        $this->comandante_id = '';
        $this->motorista_id = '';
        $this->patrulheiro_id = '';

        foreach ($escala->funcionarios as $func) {
            if ($func->pivot->papel_patrulha === 'COMANDANTE') {
                $this->comandante_id = $func->id;
            } elseif ($func->pivot->papel_patrulha === 'MOTORISTA') {
                $this->motorista_id = $func->id;
            } elseif ($func->pivot->papel_patrulha === 'PATRULHEIRO') {
                $this->patrulheiro_id = $func->id;
            }
        }

        $this->isEditing = true;
    }

    public function updatedComandanteId($value)
    {
        if ($value && $value == $this->motorista_id) $this->motorista_id = '';
        if ($value && $value == $this->patrulheiro_id) $this->patrulheiro_id = '';
    }

    public function updatedMotoristaId($value)
    {
        if ($value && $value == $this->comandante_id) $this->comandante_id = '';
        if ($value && $value == $this->patrulheiro_id) $this->patrulheiro_id = '';
    }

    public function updatedPatrulheiroId($value)
    {
        if ($value && $value == $this->comandante_id) $this->comandante_id = '';
        if ($value && $value == $this->motorista_id) $this->motorista_id = '';
    }

    public function closeModals()
    {
        $this->isEditing = false;
    }

    public function salvar()
    {
        $this->validate();

        $novoInicio = Carbon::createFromFormat('Y-m-d H:i', $this->data_escala . ' ' . $this->inicio);
        $novoTermino = (clone $novoInicio)->addHours((int)$this->turno);

        // Buscar escalas vizinhas num raio de 2 dias para verificar choque físico de horário
        $escalasVizinhas = Escala::with('funcionarios')
            ->whereBetween('data_escala', [
                (clone $novoInicio)->subDays(2)->format('Y-m-d'),
                (clone $novoTermino)->addDays(2)->format('Y-m-d')
            ])->where('id', '!=', $this->escalaId)->get();

        // VALIDAÇÃO 1: Choque de Horário da Viatura
        $conflitoViatura = $escalasVizinhas->where('viatura_id', $this->viatura_id)->first(function($esc) use ($novoInicio, $novoTermino) {
            $dataEscString = $esc->data_escala instanceof Carbon ? $esc->data_escala->format('Y-m-d') : Carbon::parse($esc->data_escala)->format('Y-m-d');
            $escInicio = Carbon::createFromFormat('Y-m-d H:i', $dataEscString . ' ' . Carbon::parse($esc->inicio)->format('H:i'));
            $escTermino = (clone $escInicio)->addHours((int)$esc->turno);
            return $novoInicio < $escTermino && $novoTermino > $escInicio;
        });

        if ($conflitoViatura) {
            $this->addError('viatura_id', 'Esta viatura já está empenhada em outra escala que conflita com este período.');
            return;
        }

        // VALIDAÇÃO 2: Choque de Horário de Funcionários (Policial Duplicado)
        $funcionarios = array_filter([$this->comandante_id, $this->motorista_id, $this->patrulheiro_id]);
        
        $conflitoFuncionario = $escalasVizinhas->first(function($esc) use ($novoInicio, $novoTermino, $funcionarios) {
            $dataEscString = $esc->data_escala instanceof Carbon ? $esc->data_escala->format('Y-m-d') : Carbon::parse($esc->data_escala)->format('Y-m-d');
            $escInicio = Carbon::createFromFormat('Y-m-d H:i', $dataEscString . ' ' . Carbon::parse($esc->inicio)->format('H:i'));
            $escTermino = (clone $escInicio)->addHours((int)$esc->turno);
            
            if ($novoInicio < $escTermino && $novoTermino > $escInicio) {
                // Sobrepõe, checar policiais
                $escFuncionarios = $esc->funcionarios->pluck('id')->toArray();
                return count(array_intersect($funcionarios, $escFuncionarios)) > 0;
            }
            return false;
        });

        if ($conflitoFuncionario) {
            $this->addError('data_escala', 'Erro de Choque! Um ou mais policiais desta guarnição já estão escalados em outro serviço concomitante.');
            return;
        }

        try {
            DB::transaction(function () use ($novoTermino) {
                $escala = Escala::updateOrCreate(
                    ['id' => $this->escalaId],
                    [
                        'data_escala' => $this->data_escala,
                        'inicio' => $this->inicio,
                        'termino' => $novoTermino->format('H:i:s'),
                        'turno' => $this->turno,
                        'viatura_id' => $this->viatura_id,
                        'status' => 'ATIVA'
                    ]
                );

                // Pivot Sync
                $syncData = [
                    $this->comandante_id => ['papel_patrulha' => 'COMANDANTE'],
                    $this->motorista_id => ['papel_patrulha' => 'MOTORISTA'],
                ];

                if ($this->patrulheiro_id) {
                    $syncData[$this->patrulheiro_id] = ['papel_patrulha' => 'PATRULHEIRO'];
                }

                $escala->funcionarios()->sync($syncData);
            });

            $this->closeModals();
            $this->dispatch('alerta', [
                'title' => 'Escala Criada!',
                'text' => 'Guarnição atrelada à Viatura com sucesso para o turno especificado.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('alerta', [
                'title' => 'Erro Tático',
                'text' => 'Conflito de Escala: A Viatura já tem um serviço lançado neste turno ou erro de DB: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        $query = Escala::with(['viatura', 'funcionarios'])->latest('data_escala');

        if (!empty($this->search)) {
            $query->whereHas('viatura', function($q) {
                $q->where('prefixo', 'like', '%'.$this->search.'%')
                  ->orWhere('placa', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.escalas.index', [
            'escalas' => $query->paginate(10),
            'viaturas' => Viatura::all(),
            'policiais' => Funcionario::where('situacao', 'ATIVO')->orderBy('nome')->get()
        ]);
    }
}
