<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Funcionario;

class ImportarFuncionariosCSV extends Command
{
    /**
     * O nome e a assinatura do comando de console.
     */
    protected $signature = 'pmp:importar-funcionarios';

    /**
     * A descrição do comando de console.
     */
    protected $description = 'Processa a carga pesada de Funcionarios Históricos em Segundo Plano usando Streams CLI p/ não corromper memória PHP.';

    /**
     * Executa o comando e varre a Stream.
     */
    public function handle()
    {
        $caminho = storage_path('app/importacoes/funcionarios.csv');

        if (!file_exists($caminho)) {
            $this->error("\n[X] Bloqueio Fatal: O arquivo CSV legado não foi encontrado no caminho base!");
            $this->info("Esperado em: {$caminho}");
            return 1;
        }

        $this->info('Iniciando o Motor Assíncrono (Segundo Plano) de Integração Legada...');
        $this->info('Criando Pipelines de Limpeza MySQL Strict e parseando o CSV.');

        // Contagem Rápida e Otimizada em Disco Seco para o Loading Bar
        $totalLinhas = 0;
        $file = new \SplFileObject($caminho, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLinhas = $file->key(); // Aproximado rápido

        $progressBar = $this->output->createProgressBar($totalLinhas);
        $progressBar->start();

        // Leitura real em Modo Stream com Fopen (Ocupa apenas 2 Megabytes de RAM)
        $handle = fopen($caminho, 'r');
        
        $cabecalho = fgetcsv($handle, 0, ',', '"');
        if (!$cabecalho) {
            $this->error("\n[X] Falha Crítica ao ler o mapa de cabeçalho do arquivo CSV.");
            return 1;
        }

        // Blindagem do Banco de Dados: Tudo ou Nada.
        DB::beginTransaction();

        $sucessos = 0;
        
        try {
            while (($dadosCsv = fgetcsv($handle, 0, ',', '"')) !== false) {
                // Segurança contra quebras de linha fantasmas no fundo do CSV
                if (count($dadosCsv) <= 1 || count($dadosCsv) !== count($cabecalho)) {
                    continue;
                }
                
                // Mapeia Nomes da Tabela <-> Valores
                $linha = array_combine($cabecalho, $dadosCsv);
                $mapeado = [];
                
                foreach ($linha as $coluna => $valorOriginal) {
                    $val = trim((string)$valorOriginal);
                    $nomeColuna = strtolower($coluna);

                    $colunasData = ['data_nascimento', 'data_inclusao', 'nascimento', 'inclusao', 'promocao', 'cnh_validade', 'created', 'modified', 'created_at', 'updated_at'];
                    $colunasIntBool = ['sigilo','planejada','planejada_restricao_interna','gdi','gde','gde_certificado','gde_horas','gde_valor','origem','posto_graduacao','anonascimento','anoinclusao', 'altura', 'peso'];

                    if ($val === '' || strtolower($val) === 'null' || str_contains($val, '0000-00-00')) {
                        if (in_array($nomeColuna, $colunasData)) {
                            $mapeado[$coluna] = null;
                        } elseif (in_array($nomeColuna, $colunasIntBool)) {
                            $mapeado[$coluna] = 0;
                        } else {
                            $mapeado[$coluna] = '**';
                        }
                        continue;
                    }
                    
                    // Se o dado for bom, adiciona
                    $mapeado[$coluna] = $val;
                }

                // Remove chaves vazias sem id (lixo gerado no final do relatorio CSV)
                if (empty($mapeado['id'])) {
                    continue; 
                }

                // O Banco Moderno precisa do Date Nativo do Laravel caso ocorra falha de Timestamps customizados da Base Velha.
                if (array_key_exists('created', $mapeado)) {
                    $mapeado['created_at'] = $mapeado['created']; 
                    unset($mapeado['created']);
                }
                if (array_key_exists('modified', $mapeado)) {
                    $mapeado['updated_at'] = $mapeado['modified'];
                    unset($mapeado['modified']);
                }

                // UPSERT ABSOLUTO: Cria ou Atualiza o Agente Baseado no ID Intocável Dele
                Funcionario::updateOrCreate(
                    ['id' => $mapeado['id']], 
                    $mapeado
                );

                $sucessos++;
                $progressBar->advance();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $progressBar->finish();
            $this->error("\n\n[X] ALERTA MYSQL: Uma quebra SQL Strict trancou a integração no momento.");
            $this->error("Falha registrada: " . $e->getMessage());
            $this->error("Última linha estabilizada: {$sucessos}");
            fclose($handle);
            return 1;
        }

        fclose($handle);
        $progressBar->finish();

        $this->info("\n\n[ OK ] Inteligência ETL Cumpriu a Operação.");
        $this->info("Linhas Sujas do Export Ignoradas. Registros injetados Perfeitamente: {$sucessos}");

        return 0;
    }
}
