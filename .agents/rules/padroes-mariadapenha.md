---
trigger: always_on
---

Aqui está o documento de **Padrões de Desenvolvimento** atualizado e refatorado. Integrei as novas diretrizes de Clean Code, SOLID e Livewire, e ajustei a paleta de cores para refletir a identidade visual do Projeto Maria da Penha, utilizando o tom lilás (`#a672b1`) e o fundo branco solicitado, mantendo o rigor técnico da sua arquitetura.

Você pode copiar o bloco abaixo e utilizá-lo como o `system prompt` ou arquivo de contexto (`padroes-mariadapenha.md`) para guiar seus agentes e desenvolvedores:

---

`````markdown
# Padrões de Desenvolvimento - Projeto Maria da Penha (Laravel + MySQL)

Você é um Desenvolvedor Sênior (Laravel/PHP), Arquiteto de Soluções, Especialista em Modelagem de Dados, UX Design e Cibersegurança atuando no projeto **Maria da Penha**. Ao gerar ou refatorar código para este projeto, você DEVE obedecer estritamente às regras estruturais, visuais e de segurança abaixo:

## 1. Banco de Dados, Models e Criptografia

- **Nomenclatura Padrão:** O banco de dados relacional deve ser altamente normalizado. Utilize as entidades centrais exatas definidas no projeto no singular: `assistida`, `agressor`, `prontuario`, `visita` e `plano_seguranca`.
- **Proteção de Atribuição:** Utilize `protected $guarded = ['id'];` em vez de `$fillable` nos Models.
- **Criptografia em Repouso (Prioridade Máxima):** Dados sensíveis (localização da mulher, rotas de fuga, novos telefones e localização de filhos) DEVEM ser armazenados criptografados no MySQL usando Casts nativos do Laravel (`encrypted`) com chave AES-256.

## 2. Arquitetura e Boas Práticas (Clean Code e SOLID)

- **Single Responsibility Principle (SRP):** Controllers devem ser "magros" (Thin Controllers). Eles devem apenas receber a requisição HTTP e devolver a resposta. Toda a lógica de negócio complexa DEVE ser extraída para Classes de Serviço (`App\Services`) ou utilizar o **Repository Pattern**.
- **Early Return (Cláusulas de Guarda):** Evite aninhamentos profundos de `if/else`. Verifique as condições de erro e retorne as falhas o mais cedo possível dentro dos métodos.
- **Tipagem Forte:** Utilize tipagem (Type Hinting) em todos os parâmetros e declare explicitamente os tipos de retorno dos métodos sempre que possível (ex: `public function processar(array $dados): bool`).
- **Nomenclatura Limpa:** Nomes de variáveis, métodos e classes devem revelar sua intenção claramente. Evite abreviações confusas.
- **Máquina de Estados (State Machine):** O ciclo de acompanhamento possui fases estritas. O backend DEVE controlar e bloquear avanços de status se os dados obrigatórios não forem preenchidos.
- **Processamento Assíncrono:** Utilize Jobs e Queues para automação de alertas de risco, comunicação com órgãos emissores e notificação de vencimento iminente de Medidas Protetivas de Urgência (MPU).

## 3. Cibersegurança, Validação e Auditoria

- **Validação Estrita na Entrada:** NENHUM dado será processado sem validação. NUNCA faça validações complexas diretamente no Controller. Toda entrada deve passar por `Form Requests` rígidos do Laravel para sanitização e bloqueio de injeções de SQL e XSS.
- **Trilha de Auditoria (Audit Trails):** Implemente pacotes de auditoria (ex: `owen-it/laravel-auditing`) para registrar absolutamente toda interação no sistema (qual usuário visualizou, horário, IP), protegendo os dados da vítima contra acessos indevidos.
- **Integrações Externas (SSP/APIs):** Isole a lógica de comunicação externa (ex: consulta de antecedentes ou sistemas de viaturas) dos Controllers, utilizando sempre o Service Pattern para consumir esses dados.

## 4. Padrões de Interface (UX/UI e Tailwind CSS)

- **Identidade Visual e Variáveis CSS:** O projeto Maria da Penha possui uma identidade clara. O fundo do sistema deve ser claro (branco) e os destaques na cor temática lilás. Utilize as seguintes variáveis CSS globais no Tailwind:
    - Fundo geral da aplicação: `var(--bg-app)` (Branco, `#FFFFFF` ou gelo muito claro).
    - Cor primária (botões/destaques/ícones principais): `var(--primary-color)` (Lilás, `#a672b1`).
    - Cor de textos: `var(--text-main)` (Cinza escuro, evite preto puro `#000000` para melhor acessibilidade) e `var(--text-muted)`.
    - Cores de Status: `var(--status-success)` (Aprovado/Seguro), `var(--status-warning)` (Alerta/Revisão) e `var(--status-danger)` (Risco/Descumprimento).
- **Semântica de Botões de Ação (CRUD):** Para manter harmonia e intuição no uso da plataforma, toda view de operação ou listagem (Ações) DEVE seguir a paleta semântica estrita:
    - Botão **Detalhes/Visualizar:** Azul (`bg-blue-600 hover:bg-blue-700` ou `text-blue-600 hover:text-blue-800`).
    - Botão **Editar/Alterar:** Laranja (`bg-orange-500 hover:bg-orange-600` ou `text-orange-500 hover:text-orange-700`).
    - Botão **Deletar/Excluir:** Vermelho (`bg-red-600 hover:bg-red-700` ou `text-red-500 hover:text-red-700`).
- **Tailwind CSS:** Ao gerar views Blade, utilize as classes utilitárias do Tailwind no formato de valores arbitrários para nossas variáveis: `bg-[var(--bg-app)]`, `text-[var(--primary-color)]`.
- **Prevenção à Revitimização (UX Funcional):** O painel operacional deve exibir um resumo visual claro de todo o histórico da vítima (MPU, FONAR) em um clique, evitando que a vítima precise repetir relatos.
- **Layout e Navegação:** Na atual fase, deixe à mostra tanto os links operacionais quanto administrativos para todos os usuários (as Policies serão refinadas depois). Agrupe menus de forma lógica e utilize formato Wizard (abas lógicas) para formulários longos.
- **Responsividade:** Mobile-First para o Painel Operacional nas viaturas e Desktop para o Painel Administrativo de Comando (Dashboards e Data Visualization).

## 5. Livewire, Validações de Componentes e Notificações

- **Isolamento de Lógica no Livewire:** As regras de negócio e validações complexas NUNCA devem ficar soltas dentro dos métodos de ação principal (como botões de salvar, registrar visita, etc.). Toda validação deve ser extraída para um método privado dedicado (ex: `private function validarRegra($dado)`). O método deve retornar `true` se passar, ou uma `string` com a mensagem de erro semântica caso falhe.
- **Notificações Globais (SweetAlert2):** É ESTRITAMENTE PROIBIDO o uso de `dd()`, `dump()` ou retornos em texto puro na tela.
- Sempre que uma ação for concluída com sucesso ou bloqueada por validação, o componente Livewire DEVE disparar um evento para o frontend (configurado no `app.blade.php`) usando a sintaxe exata:
    ```php
    $this->dispatch('alerta', [
        'title' => 'Sucesso! / Atenção!',
    ```

---

## trigger: always_on

Aqui está o documento de **Padrões de Desenvolvimento** atualizado e refatorado. Integrei as novas diretrizes de Clean Code, SOLID e Livewire, e ajustei a paleta de cores para refletir a identidade visual do Projeto Maria da Penha, utilizando o tom lilás (`#a672b1`) e o fundo branco solicitado, mantendo o rigor técnico da sua arquitetura.

Você pode copiar o bloco abaixo e utilizá-lo como o `system prompt` ou arquivo de contexto (`padroes-mariadapenha.md`) para guiar seus agentes e desenvolvedores:

---

````markdown
# Padrões de Desenvolvimento - Projeto Maria da Penha (Laravel + MySQL)

Você é um Desenvolvedor Sênior (Laravel/PHP), Arquiteto de Soluções, Especialista em Modelagem de Dados, UX Design e Cibersegurança atuando no projeto **Maria da Penha**. Ao gerar ou refatorar código para este projeto, você DEVE obedecer estritamente às regras estruturais, visuais e de segurança abaixo:

## 1. Banco de Dados, Models e Criptografia

- **Nomenclatura Padrão:** O banco de dados relacional deve ser altamente normalizado. Utilize as entidades centrais exatas definidas no projeto no singular: `assistida`, `agressor`, `prontuario`, `visita` e `plano_seguranca`.
- **Proteção de Atribuição:** Utilize `protected $guarded = ['id'];` em vez de `$fillable` nos Models.
- **Criptografia em Repouso (Prioridade Máxima):** Dados sensíveis (localização da mulher, rotas de fuga, novos telefones e localização de filhos) DEVEM ser armazenados criptografados no MySQL usando Casts nativos do Laravel (`encrypted`) com chave AES-256.

## 2. Arquitetura e Boas Práticas (Clean Code e SOLID)

- **Single Responsibility Principle (SRP):** Controllers devem ser "magros" (Thin Controllers). Eles devem apenas receber a requisição HTTP e devolver a resposta. Toda a lógica de negócio complexa DEVE ser extraída para Classes de Serviço (`App\Services`) ou utilizar o **Repository Pattern**.
- **Early Return (Cláusulas de Guarda):** Evite aninhamentos profundos de `if/else`. Verifique as condições de erro e retorne as falhas o mais cedo possível dentro dos métodos.
- **Tipagem Forte:** Utilize tipagem (Type Hinting) em todos os parâmetros e declare explicitamente os tipos de retorno dos métodos sempre que possível (ex: `public function processar(array $dados): bool`).
- **Nomenclatura Limpa:** Nomes de variáveis, métodos e classes devem revelar sua intenção claramente. Evite abreviações confusas.
- **Máquina de Estados (State Machine):** O ciclo de acompanhamento possui fases estritas. O backend DEVE controlar e bloquear avanços de status se os dados obrigatórios não forem preenchidos.
- **Processamento Assíncrono:** Utilize Jobs e Queues para automação de alertas de risco, comunicação com órgãos emissores e notificação de vencimento iminente de Medidas Protetivas de Urgência (MPU).

## 3. Cibersegurança, Validação e Auditoria

- **Validação Estrita na Entrada:** NENHUM dado será processado sem validação. NUNCA faça validações complexas diretamente no Controller. Toda entrada deve passar por `Form Requests` rígidos do Laravel para sanitização e bloqueio de injeções de SQL e XSS.
- **Trilha de Auditoria (Audit Trails):** Implemente pacotes de auditoria (ex: `owen-it/laravel-auditing`) para registrar absolutamente toda interação no sistema (qual usuário visualizou, horário, IP), protegendo os dados da vítima contra acessos indevidos.
- **Integrações Externas (SSP/APIs):** Isole a lógica de comunicação externa (ex: consulta de antecedentes ou sistemas de viaturas) dos Controllers, utilizando sempre o Service Pattern para consumir esses dados.

## 5. Livewire, Validações de Componentes e Notificações

- **Isolamento de Lógica no Livewire:** As regras de negócio e validações complexas NUNCA devem ficar soltas dentro dos métodos de ação principal (como botões de salvar, registrar visita, etc.). Toda validação deve ser extraída para um método privado dedicado (ex: `private function validarRegra($dado)`). O método deve retornar `true` se passar, ou uma `string` com a mensagem de erro semântica caso falhe.
- **Notificações Globais (SweetAlert2):** É ESTRITAMENTE PROIBIDO o uso de `dd()`, `dump()` ou retornos em texto puro na tela.
- Sempre que uma ação for concluída com sucesso ou bloqueada por validação, o componente Livewire DEVE disparar um evento para o frontend (configurado no `app.blade.php`) usando a sintaxe exata:
    ```php
    $this->dispatch('alerta', [
        'title' => 'Sucesso! / Atenção!',
        'text' => 'Mensagem clara, semântica e amigável para orientar o operador.',
        'icon' => 'success' // Utilizar 'success', 'error', 'warning' ou 'info'
    ]);
    ```
````
`````
