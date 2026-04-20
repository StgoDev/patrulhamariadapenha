<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // Assistidas
    Route::get('/assistidas', \App\Livewire\Assistidas\Index::class)->name('assistidas.index');
    Route::get('/assistidas/nova', \App\Livewire\Assistidas\Create::class)->name('assistidas.create');
    
    // Prontuários (Acompanhamentos)
    Route::get('/acompanhamentos', \App\Livewire\Acompanhamentos\Index::class)->name('acompanhamentos.index');
    Route::get('/acompanhamentos/novo', \App\Livewire\Acompanhamentos\Create::class)->name('acompanhamentos.create');
    
    // Agressores
    Route::get('/agressores', \App\Livewire\Agressores\Index::class)->name('agressores.index');
    Route::get('/agressores/novo', \App\Livewire\Agressores\Create::class)->name('agressores.create');
    
    // Novas rotas operacionais e de comando
    Route::get('/viaturas', \App\Livewire\Viaturas\Index::class)->name('viaturas.index');
    Route::get('/escalas', \App\Livewire\Escalas\Index::class)->name('escalas.index');
    Route::get('/rotas', \App\Livewire\Rotas\Index::class)->name('rotas.index');
    Route::get('/visitas', \App\Livewire\Visitas\Index::class)->name('visitas.index');
    Route::get('/visitas/registrar', \App\Livewire\Visitas\Registrar::class)->name('visitas.registrar');
    Route::get('/mapas', \App\Livewire\Mapas\Index::class)->name('mapas.index');
    Route::get('/relatorios/fonar', \App\Livewire\Relatorios\Fonar::class)->name('relatorios.fonar');
    
    // Administrativo
    Route::get('/usuarios', \App\Livewire\Usuarios\Index::class)->name('usuarios.index');
    Route::get('/funcionarios', \App\Livewire\Funcionarios\Index::class)->name('funcionarios.index');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
