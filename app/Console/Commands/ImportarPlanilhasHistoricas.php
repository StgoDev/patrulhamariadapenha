<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Assistida;
use App\Models\Agressor;
use App\Models\Acompanhamento;
use Shuchkin\SimpleXLSX;

class ImportarPlanilhasHistoricas extends Command
{
    /**
     * O nome e assinatura do comando.
     *
     * @var string
     */
    protected $signature = 'pmp:importar-csv {arquivo}';

    /**
     * A descrição do comando console.
     *
     * @var string
     */
    protected $description = 'Script ETL robusto para importar planilhas históricas em XLSX nativo (Com Super Sanitização)';

    /**
     * Executa o comando.
     */
    public function handle()
    {
        $arquivo = $this->argument('arquivo');
        $caminho = storage_path("app/importacoes/{$arquivo}");

        if (!file_exists($caminho)) {
            $this->error("ERRO FATAL: Arquivo não encontrado em: {$caminho}");
            return 1;
        }

        $this->info("Iniciando rotina ETL Tática do projeto Maria da Penha (Arquivo: {$arquivo})");

        $xlsx = SimpleXLSX::parse($caminho);
        if (!$xlsx) {
            $this->error("ERRO FATAL: Falha ao ler a planilha XLSX. Erro: " . SimpleXLSX::parseError());
            return 1;
        }

        $linhas = $xlsx->rows();
        $progressBar = $this->output->createProgressBar(count($linhas));
        $progressBar->start();

        $cabecalho = [];
        $sucessos = 0;
        $linhasLixo = 0;

        DB::beginTransaction();

        try {
            foreach ($linhas as $i => $linhaBase) {
                // Diretriz 1: Mapeamento de Cabeçalho Dinâmico Rigoroso (Primeira Linha do Excel real)
                // Se o cabeçalho ainda não foi montado, consideraremos a primeira linha que não for totalmente vazia como header.
                if (empty($cabecalho)) {
                    $temConteudo = false;
                    foreach ($linhaBase as $idx => $nomeColuna) {
                        $col = trim((string)$nomeColuna);
                        if (!empty($col)) {
                            // Armazena no dicionário o índice numérico exato apontando pro Nome da Coluna Maiúsculo
                            $cabecalho[$idx] = Str::upper($col);
                            $temConteudo = true;
                        }
                    }
                    if ($temConteudo) {
                        $progressBar->advance();
                        continue; 
                    }
                }

                // Cria o array Mapeado $linha
                $linha = [];
                foreach ($cabecalho as $idx => $nomeColunaMapeada) {
                    $linha[$nomeColunaMapeada] = trim((string)($linhaBase[$idx] ?? ''));
                }

                // Chaves EXATAS requeridas
                $processoData   = $linha['PROCESSO'] ?? '';
                $requerenteData = $linha['REQUERENTE'] ?? '';
                $requeridoData  = $linha['REQUERIDO'] ?? '';
                $enderecoData   = $linha['ENDEREÇO'] ?? '';
                $situacaoData   = $linha['SITUAÇÃO'] ?? '';
                
                // Mapeamento extra seguro para Data caso exista (pois não estava no dicionário fechado, é opcional)
                $dataStr = $linha['DATA'] ?? ($linha['DATA DE INÍCIO'] ?? '');

                // Diretriz 2: Filtro de Linhas Lixo com early returns
                $processoUpper = Str::upper($processoData);
                $mesesRegex = '/(JANEIRO|FEVEREIRO|MARÇO|ABRIL|MAIO|JUNHO|JULHO|AGOSTO|SETEMBRO|OUTUBRO|NOVEMBRO|DEZEMBRO)/i';

                if (empty($processoData) || preg_match($mesesRegex, $processoUpper)) {
                    $linhasLixo++;
                    $progressBar->advance();
                    continue; // Pula sumariamente o separador de mês ou a linha vazia!
                }

                // Diretriz 3: Fallback Estrito de Nomes!
                if (empty($requerenteData)) {
                    $requerenteData = "NÃO INFORMADO NA PLANILHA ANTIGA";
                }
                if (empty($requeridoData)) {
                    $requeridoData = "NÃO INFORMADO NA PLANILHA ANTIGA";
                }

                $requerenteUpper = Str::upper($requerenteData);
                $requeridoUpper  = Str::upper($requeridoData);

                // Sanidade de Data Genérica
                $dataInicioSegura = now(); 
                if (!empty($dataStr)) {
                    try {
                        if (str_contains($dataStr, '/')) {
                            $dataInicioSegura = Carbon::createFromFormat('d/m/Y', $dataStr);
                        } else {
                            $dataInicioSegura = Carbon::parse($dataStr);
                        }
                    } catch (\Exception $e) {
                        $dataInicioSegura = now();
                    }
                }

                // Higiene do Status
                $sit = Str::upper($situacaoData);
                $situacaoDefinitiva = match (true) {
                    str_contains($sit, 'ENCERRAD') => 'ENCERRADA',
                    str_contains($sit, 'RECUS')    => 'RECUSOU',
                    str_contains($sit, 'PAUS')     => 'PAUSA',
                    str_contains($sit, 'REATIV')   => 'REATIVOU',
                    default                        => 'ATIVA',
                };

                // Passo A: Assistida Otimizada
                $assistida = Assistida::firstOrCreate(
                    ['nome' => $requerenteUpper],
                    ['endereco' => !empty($enderecoData) ? $enderecoData : 'Não Registrado']
                );

                // Passo B: Agressor Otimizado
                $agressor = Agressor::firstOrCreate(
                    ['nome' => $requeridoUpper]
                );

                // Passo C: Atualização ou Criação do Prontuário garantindo chave forte (PROCESSO)
                $acompanhamento = Acompanhamento::updateOrCreate(
                    ['numero_processo' => $processoData],
                    [
                        'assistida_id' => $assistida->id,
                        'agressor_id'  => $agressor->id,
                        'situacao'     => $situacaoDefinitiva,
                        'ano_processo' => $dataInicioSegura->year,
                        'data_inicio'  => $dataInicioSegura->format('Y-m-d'),
                    ]
                );

                $sucessos++;
                $progressBar->advance();
            }

            // Apenas aciona o banco se percorrer todas as linhas fielmente
            DB::commit();

        } catch (\Exception $error) {
            DB::rollBack();
            $this->error("\n\n[ERRO CRÍTICO] Falha no import. Nenhuma linha foi salva. Rollback de segurança...");
            $this->error("Detalhes da excessão: " . $error->getMessage());
            return 1;
        }

        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("=== Relatório Tático de ETL ===");
        $this->info("Situação: Sucesso Absoluto na Carga!");
        $this->info("Prontuários e Vínculos Sincronizados/Carregados: {$sucessos}");
        $this->info("Linhas Sujas/Separadores (JANEIRO, DEZEMBRO) Filtrados com precisão: {$linhasLixo}");

        return 0;
    }
}
