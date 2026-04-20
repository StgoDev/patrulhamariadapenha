<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="w-64 bg-[var(--primary-color)] text-white flex flex-col h-full shadow-lg shrink-0 fixed inset-y-0 left-0 z-[50] md:relative transition-transform duration-300 ease-in-out">
    <!-- Logo & Branding Oficial -->
    <div class="py-5 flex items-center justify-center border-b border-white/20 shrink-0 mb-2">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-2">
            <img src="{{ asset('midias_oficiais/brasao.jpg') }}"
                class="w-24 h-24 object-contain drop-shadow-md rounded-full bg-white p-0.5"
                alt="Brasão da Corporação" />
            <span class="text-sm font-bold text-white text-center uppercase tracking-wide mt-1">Patrulha Maria da
                Penha</span>
        </a>
    </div>

    <!-- Navigation Area -->
    <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">

        <!-- Grupo: Comando -->
        <div>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('dashboard') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Grupo: Operacional -->
        <div>
            <h3 class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/50 mb-2">Operacional</h3>
            <div class="space-y-1">
                <a href="{{ route('viaturas.index') }}"
                    class="{{ request()->routeIs('viaturas.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('viaturas.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    <span class="font-medium text-sm">Viaturas</span>
                </a>
                <a href="{{ route('escalas.index') }}"
                    class="{{ request()->routeIs('escalas.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('escalas.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                    <span class="font-medium text-sm">Escalas</span>
                </a>

                <a href="{{ route('visitas.index') }}"
                    class="{{ request()->routeIs('visitas.index') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('visitas.index') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                    </svg>
                    <span class="font-medium text-sm">Visitas</span>
                </a>
            </div>
        </div>

        <!-- Grupo: Gestão de Casos -->
        <div>
            <h3 class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/50 mb-2">Gestão de Casos</h3>
            <div class="space-y-1">
                <a href="{{ route('acompanhamentos.index') }}"
                    class="{{ request()->routeIs('acompanhamentos.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('acompanhamentos.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span class="font-medium text-sm">Prontuários</span>
                </a>
                <a href="{{ route('assistidas.index') }}"
                    class="{{ request()->routeIs('assistidas.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('assistidas.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span class="font-medium text-sm">Vítimas</span>
                </a>
                <a href="{{ route('agressores.index') }}"
                    class="{{ request()->routeIs('agressores.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('agressores.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-medium text-sm">Agressores</span>
                </a>
            </div>
        </div>



        <!-- Grupo: Administração -->
        <div>
            <h3 class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/50 mb-2 mt-4">Administração</h3>
            <div class="space-y-1">
                <a href="{{ route('usuarios.index') }}"
                    class="{{ request()->routeIs('usuarios.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('usuarios.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="font-medium text-sm">Contas (Acessos)</span>
                </a>
                <a href="{{ route('funcionarios.index') }}"
                    class="{{ request()->routeIs('funcionarios.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-white/70 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }} flex items-center gap-3 px-3 py-2 transition-colors group rounded-r-md">
                    <svg class="w-5 h-5 shrink-0 text-white/70 group-hover:text-white {{ request()->routeIs('funcionarios.*') ? 'text-white' : '' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    <span class="font-medium text-sm">Funcionários</span>
                </a>
            </div>
        </div>

    </nav>
</aside>