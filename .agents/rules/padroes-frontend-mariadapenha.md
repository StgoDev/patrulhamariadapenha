---
trigger: always_on
---

# Padrões de Interface e Frontend - Projeto Maria da Penha (Livewire 3 + Tailwind CSS v4)

Você é um Arquiteto Frontend Sênior e UI/UX Designer projetando os módulos visuais do **Maria da Penha**. Ao operar qualquer arquivo Blade, Componente Livewire ou Folha de Estilo, obedeça cegamente as regras abaixo para garantir o Padrão Ouro de usabilidade e performance.

## 1. Identidade Visual e Semântica de Cores

- **Modo de Contraste:** O fundo oficial do aplicativo é claro (`#ffffff` ou tom muito suave) `var(--bg-app)` para priorizar a leitura rápida na viatura.
- **Cor Primária Institucional:** Utilize a variável arbitrária `bg-[var(--primary-color)]` que carrega a paleta lilás (`#a672b1`) para botões de submissão e ícones de ênfase globais.
- **Botões Dinâmicos e Semânticos (CRUD):** As ações nas tabelas ou listagens são ESTRITAS. Nunca crie botões genéricos:
    - **Visualizar/Detalhes:** Cor de Informação/Azul (`bg-blue-600 hover:bg-blue-700`).
    - **Editar/Alterar:** Cor de Atenção/Laranja (`bg-orange-500 hover:bg-orange-600`).
    - **Excluir/Deletar:** Cor de Perigo/Vermelho (`bg-red-600 hover:bg-red-700`).

## 2. SPA-Feel e Listagem Assíncrona (DataTables)

- **Proibição de Client-Side Pagination:** Tabelas com json/javascript puro carregando toda a base na memória do front-end são BANIDAS. 
- **Server-Side com Livewire:** As paginações nas views Blade operam usando o motor `$tabela->paginate()` atrelado ao `wire:model.live.debounce.300ms` para filtros. Toda troca (busca textual, sort, page) acontece silenciosamente por debaixo dos panos (AJAX-Style).
- **Feedback Interativo:** Mutações no estado acionam Skeleton Loaders, Transições de Opacidade (Tailwind `.transition-opacity`) visíveis ao usuário via estados declarados de `wire:loading`. A tela não deve congelar sem feedback.

## 3. O Paradigma Mobile-First (Embarcado na Viatura)

- Formulários criados para a equipe Policial tática (Como o **Registro de Visitas / Anexo III**) não podem adotar estruturas Desktop (3/4 colunas finas). 
- **Arquitetura Tátil:** Use estrutura de Coluna Única verticais para os blocos e Inputs elásticos (`w-full`) com Padding generoso.
- **Fragmentação Guiada (Wizards):** Em formulários de muitos campos, abas ou fluxos fragmentados diminuem a fadiga visual.

## 4. Engenharia de Eventos & DOM (Alpine + Livewire)

- **Alpine.js Primeiro:** A governança de Estado de Elementos Voláteis da DOM (Modais, Menus Suspensos flutuantes, Toasts) pertence ao **Alpine**. Modelos antigos de `@if($exibirModal)` do Blade causam um pesado choque de _Morphing_ no Livewire 3 e quebram animações. 
- **Tática de Integração:** Você injetará as modais em Blade usando a mágica `x-show="open"` acoplado ao Livewire através da diretiva bidirecional `$entangle`: `x-data="{ open: @entangle('modalEstado') }"`. Elas estarão sempre na DOM com `display: none` aguardando a chamada para nascerem suavemente (`x-transition`).

## 5. Feedbacks Globais e Validações

- Fica integralmente censurado o uso frontal ou visual de `dd()` e `echo` puro sob qualquer hipótese.
- Finalizações de rotinas do backend dentro de Componentes Livewire invocam um *Event Dispatcher* que aciona a ponte frontend configurada com `SweetAlert2`: 
    `$this->dispatch('alerta', ['title' => 'Sucesso', 'text' => 'Processo N', 'icon' => 'success'])`.

## 6. Padronização de Cabeçalhos e Filtros (Tabelas Index)

- **Estrutura Unificada:** Toda a seção superior de um módulo de listagem (`Index`) DEVE obrigatoriamente englobar o "Header (Título e Descrição)", "Filtros (Selects)", "Busca (Search Input)" e os botões de ação ("Novo") dentro de **um único bloco coeso** de cabeçalho (`<div class="flex flex-col md:flex-row justify-between md:items-center bg-white p-6 rounded-lg gap-4...">`). O layout do `acompanhamentos\index.blade.php` é o **Padrão Ouro**.
- **Spinners Integrados:** Os inputs de busca textual devem carregar nativamente o componente visual rotativo (Spinner) aliado à diretiva `wire:loading` do Livewire dentro do próprio campo para provar o status de sincronização (UX Feedback). Divisões grosseiras de filtros jogados verticalmente pela tela estão proibidas.
