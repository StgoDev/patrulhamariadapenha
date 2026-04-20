<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Autenticação | Maria da Penha</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Coluna Institucional Esquerda (Desktop Apenas) -->
        <div
            class="hidden md:flex md:w-1/2 lg:w-3/5 bg-[var(--primary-color)] justify-center items-center relative overflow-hidden shadow-2xl z-10">

            <!-- Fundo Fotográfico Oficial da Corporação -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('midias_oficiais/backgroud.jpg') }}');"></div>
            <!-- Película Institucional para Legibilidade do Texto sobre a Foto -->
            <div class="absolute inset-0 bg-[var(--primary-color)]/85 mix-blend-multiply"></div>

            <div class="relative z-20 flex flex-col items-center text-center p-12">
                <h1 class="text-4xl lg:text-6xl font-black text-white tracking-tight drop-shadow-md leading-tight">
                    Patrulha Maria da Penha
                </h1>

                <div class="mt-8 w-24 h-1.5 bg-white/50 rounded-full"></div>

                <!-- Chancelaria Institucional PMPI -->
                <div class="mt-8 flex flex-col items-center gap-3 opacity-90 hover:opacity-100 transition-opacity">
                    <img src="{{ asset('midias_oficiais/pmpi.png') }}"
                        class="w-32 h-32 sm:w-40 sm:h-40 object-contain drop-shadow-2xl" alt="Escudo PMPI">
                    <h3 class="text-white tracking-[0.2em] font-bold uppercase text-sm lg:text-base drop-shadow-md">
                        Polícia Militar do Piauí
                    </h3>
                </div>

                <p class="mt-8 text-xl lg:text-2xl text-white/90 max-w-lg font-medium leading-relaxed drop-shadow-sm">
                    Sistema Integrado de Controle Administrativo da Patrulha Maria da Penha.
                </p>
            </div>
        </div>

        <!-- Coluna de Formulário Direita (Mobile e Desktop) -->
        <div
            class="w-full md:w-1/2 lg:w-2/5 min-h-screen flex items-center justify-center p-4 sm:p-8 lg:p-12 bg-gray-50/50">
            <div class="w-full max-w-md">

                <!-- Slot da View Filha (Login/Registro) -->
                <div class="p-8 sm:p-10 rounded-2xl relative w-full">

                    <!-- Área do Brasão Fixo e Cru (Sem Animações) -->
                    <div class="flex flex-col items-center mb-6">
                        <img src="{{ asset('midias_oficiais/brasao.jpg') }}"
                            class="w-24 h-24 sm:w-28 sm:h-28 object-contain" alt="Brasão Corporação">
                    </div>

                    {{ $slot }}
                </div>

                <div
                    class="mt-8 flex flex-col items-center justify-center text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <img src="{{ asset('midias_oficiais/sicadlogo.png') }}" alt="SICAD Desenvolvimentos"
                        class="h-10 w-auto object-contain mb-3">
                    <span>&copy; {{ date('Y') }} INOVA - PMPI.</span>
                </div>
            </div>
        </div>

    </div>

</body>

</html>