<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Maria da Penha') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-[var(--text-main)] overflow-hidden" x-data="{ sidebarOpen: false }">
        <x-banner />

        <!-- Overlay Mobile da Sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-[#000000a0] backdrop-blur-sm z-[45] md:hidden" style="display: none;"></div>

        <div class="flex h-screen overflow-hidden bg-[var(--bg-app)]">
            <!-- Sidebar Component -->
            <x-sidebar />

            <!-- Content Area (Right Side) -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                <!-- Topbar -->
                <livewire:layout.navigation />

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow-sm z-10">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 w-full p-4 md:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('alerta', (event) => {
                    let data = Array.isArray(event) ? event[0] : event;
                    Swal.fire({
                        title: data.title,
                        text: data.text,
                        icon: data.icon, // 'success', 'error', 'warning', 'info'
                        confirmButtonColor: '#a672b1',
                    });
                });
            });
        </script>
    </body>
</html>
