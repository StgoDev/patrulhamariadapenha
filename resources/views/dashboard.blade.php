<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 h-full flex flex-col">
        <div class="w-full px-4 sm:px-6 lg:px-8 flex-1">
            <livewire:dashboard.estatisticas />
        </div>
    </div>
</x-app-layout>
