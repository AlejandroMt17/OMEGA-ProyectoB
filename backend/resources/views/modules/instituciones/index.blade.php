{{--
    @file index.blade.php
    @description Lista de instituciones del Docente
    @version 1.1.0
--}}
@extends('layouts.app')
@section('title', 'Mis Instituciones')
@section('content')

{{-- Título y botón --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-heading font-semibold text-omg-nile">Mis Instituciones</h1>
        <p class="text-sm font-body text-omg-kashmir mt-1">
            Gestiona los espacios donde impartes clases
        </p>
    </div>
    <a href="{{ route('ca.instituciones.create') }}"
       class="flex items-center gap-2 bg-omg-coral hover:bg-omg-coral-dark text-white font-heading font-semibold px-4 py-2.5 rounded-lg transition-colors text-sm">
        <i class="fa-solid fa-plus"></i>
        Nueva institución
    </a>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-omg-kashmir-dark overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-omg-kashmir-dark bg-omg-chardon">
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">
                    Institución
                </th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">
                    Logo
                </th>
                <th class="text-right px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-omg-kashmir-dark">
            @forelse ($instituciones as $institucion)
                <tr class="hover:bg-omg-chardon transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-body font-semibold text-omg-dark">
                            {{ $institucion->nombre }}
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        @if ($institucion->logo)
                            <img src="{{ $institucion->logo }}"
                                 alt="Logo {{ $institucion->nombre }}"
                                 class="h-10 w-auto object-contain rounded"
                                 onerror="this.onerror=null;this.src='';this.parentElement.innerHTML='<span class=\'text-xs text-omg-kashmir\'>Sin logo</span>'">
                        @else
                            <span class="text-xs text-omg-kashmir">Sin logo</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Seleccionar institución activa --}}
                            <form method="POST" action="{{ route('ca.instituciones.seleccionar', $institucion->id_institucion) }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-1.5 px-3 py-1.5 {{ session('institucion_id') == $institucion->id_institucion ? 'bg-omg-coral text-white' : 'bg-omg-chardon hover:bg-omg-coral hover:text-white text-omg-nile' }} rounded-lg text-xs font-body transition-colors">
                                    <i class="fa-solid fa-check"></i>
                                    {{ session('institucion_id') == $institucion->id_institucion ? 'Activa' : 'Seleccionar' }}
                                </button>
                            </form>
                            <a href="{{ route('ca.instituciones.edit', $institucion->id_institucion) }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 bg-omg-pastel hover:bg-omg-nile hover:text-white text-omg-nile rounded-lg text-xs font-body transition-colors">
                                <i class="fa-regular fa-pen-to-square"></i>
                                Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('ca.instituciones.destroy', $institucion->id_institucion) }}"
                                  onsubmit="return confirm('Esta acción no se puede deshacer')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-500 hover:text-white text-red-500 rounded-lg text-xs font-body transition-colors">
                                    <i class="fa-solid fa-delete-left"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-5 py-12 text-center">
                        <i class="fa-solid fa-building-columns text-omg-kashmir fa-2x mb-3"></i>
                        <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
                        <p class="text-xs font-body text-omg-kashmir mt-1">
                            Crea tu primera institución con el botón de arriba
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
