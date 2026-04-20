<div class="space-y-6">
    <!-- Adicionando o ApexCharts via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Cabeçalho e Toggles de Tempo -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-lg shadow-sm gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[var(--primary-color)]">Painel de Comando</h2>
            <p class="text-sm text-gray-500">Acompanhamento consolidado de Produtividade Tática</p>
        </div>

        <div class="flex bg-gray-100 p-1 rounded-lg">
            <button wire:click="$set('periodo', 'diario')" 
                class="px-4 py-2 text-sm font-semibold rounded-md transition-colors {{ $periodo === 'diario' ? 'bg-white shadow text-[var(--primary-color)]' : 'text-gray-600 hover:bg-gray-200' }}">
                Diário (Hoje)
            </button>
            <button wire:click="$set('periodo', 'mensal')" 
                class="px-4 py-2 text-sm font-semibold rounded-md transition-colors {{ $periodo === 'mensal' ? 'bg-white shadow text-[var(--primary-color)]' : 'text-gray-600 hover:bg-gray-200' }}">
                Mensal
            </button>
            <button wire:click="$set('periodo', 'anual')" 
                class="px-4 py-2 text-sm font-semibold rounded-md transition-colors {{ $periodo === 'anual' ? 'bg-white shadow text-[var(--primary-color)]' : 'text-gray-600 hover:bg-gray-200' }}">
                Anual
            </button>
        </div>
    </div>

    <!-- Cards de KPIs (Indicadores) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card: Prontuários -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-[var(--primary-color)] flex items-center justify-between group">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Prontuários {{ $periodo == 'diario' ? 'Hoje' : ($periodo == 'mensal' ? 'Este Mês' : 'Neste Ano') }}</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ number_format($kpis['prontuarios'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-[var(--primary-color)] rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        </div>

        <!-- Card: Vítimas -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-orange-500 flex items-center justify-between group">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Vítimas {{ $periodo == 'diario' ? 'Hoje' : ($periodo == 'mensal' ? 'Este Mês' : 'Neste Ano') }}</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ number_format($kpis['vitimas'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        </div>

        <!-- Card: Agressores -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500 flex items-center justify-between group">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Agressores {{ $periodo == 'diario' ? 'Hoje' : ($periodo == 'mensal' ? 'Este Mês' : 'Neste Ano') }}</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ number_format($kpis['agressores'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <!-- Card: Visitas -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500 flex items-center justify-between group">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Visitas {{ $periodo == 'diario' ? 'Hoje' : ($periodo == 'mensal' ? 'Este Mês' : 'Neste Ano') }}</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ number_format($kpis['visitas'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Container do Gráfico (ApexCharts injetado via Alpine) -->
    <div class="bg-white p-6 rounded-lg shadow-sm w-full" wire:ignore>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800">Evolução Temporal de Entradas</h3>
            <div class="livewire-loading-spinner text-[var(--primary-color)] hidden" wire:loading.class.remove="hidden" wire:target="periodo">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <div 
            x-data="chartManager()" 
            x-init="renderChart({{ $chartData }})" 
            @content-changed.window="updateChart($event.detail[0])"
            id="estatisticas-chart" 
            class="w-full"
            style="min-height: 400px;"
        ></div>
    </div>

    @script
    <script>
        Alpine.data('chartManager', () => ({
            chart: null,

            renderChart(chartData) {
                if (typeof ApexCharts === 'undefined') {
                    console.error("ApexCharts not loaded");
                    return;
                }

                const options = {
                    series: chartData.series,
                    chart: {
                        height: 400,
                        type: 'area', // Area chart gives a very premium feel
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        zoom: { enabled: false },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: {
                        categories: chartData.labels,
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            style: { colors: '#9ca3af' }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#9ca3af' },
                            formatter: (value) => { return Math.round(value) }
                        }
                    },
                    grid: {
                        borderColor: '#f3f4f6',
                        strokeDashArray: 4,
                        padding: { top: 0, right: 0, bottom: 0, left: 10 }
                    },
                    tooltip: {
                        theme: 'light',
                        y: { formatter: function (val) { return val + " registros" } }
                    }
                };

                this.chart = new ApexCharts(document.querySelector("#estatisticas-chart"), options);
                this.chart.render();
            },

            updateChart(chartData) {
                if(this.chart) {
                    this.chart.updateOptions({
                        xaxis: { categories: chartData.labels }
                    });
                    this.chart.updateSeries(chartData.series);
                }
            }
        }));

        // Integrating Livewire hook to send updated chartData downward
        $wire.on('periodo-changed', (data) => {
            let event = new CustomEvent('content-changed', { detail: [data] });
            window.dispatchEvent(event);
        });
    </script>
    @endscript
</div>
