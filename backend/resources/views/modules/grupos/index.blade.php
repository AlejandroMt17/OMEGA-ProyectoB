{{--
    @file index.blade.php
    @description Lista de grupos/aulas del Docente
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Mis Aulas')
@section('content')

{{-- Título y botón --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-heading font-semibold text-omg-nile">Mis Aulas</h1>
        <p class="text-sm font-body text-omg-kashmir mt-1">
            Gestiona tus grupos y genera códigos de invitación
        </p>
    </div>
    <a href="{{ route('ca.grupos.create') }}"
       class="flex items-center gap-2 bg-omg-coral hover:bg-omg-coral-dark text-white font-heading font-semibold px-4 py-2.5 rounded-lg transition-colors text-sm">
        <i class="fa-solid fa-plus"></i>
        Nueva aula
    </a>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-omg-kashmir-dark overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-omg-kashmir-dark bg-omg-chardon">
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Grupo</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Materia</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Periodo</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Alumnos</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Código</th>
                <th class="text-right px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-omg-kashmir-dark">
            @forelse ($grupos as $grupo)
                <tr class="hover:bg-omg-chardon transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-body font-semibold text-omg-dark">{{ $grupo->nombre }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-body text-omg-dark">{{ $grupo->materia }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-body text-omg-kashmir">{{ $grupo->periodo }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-body text-omg-dark">{{ $grupo->no_alumnos }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @if ($grupo->codigo_inv)
                            <span class="bg-omg-chardon text-omg-nile font-heading font-semibold text-xs px-2 py-1 rounded-lg">
                                {{ $grupo->codigo_inv }}
                            </span>
                        @else
                            <form method="POST" action="{{ route('ca.grupos.codigo-inv', $grupo->id_grupo) }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs font-body text-omg-nile-light hover:underline">
                                    <i class="fa-solid fa-rotate-right me-1"></i>Generar
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('ca.grupos.sesiones', $grupo->id_grupo) }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-omg-nile hover:bg-omg-nile-dark text-white rounded-lg text-xs font-body transition-colors">
                                <i class="fa-solid fa-calendar-day"></i>
                                Sesiones
                            </a>
                            <a href="{{ route('ca.grupos.edit', $grupo->id_grupo) }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 bg-omg-pastel hover:bg-omg-nile hover:text-white text-omg-nile rounded-lg text-xs font-body transition-colors">
                                <i class="fa-regular fa-pen-to-square"></i>
                                Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('ca.grupos.destroy', $grupo->id_grupo) }}"
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
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fa-solid fa-chalkboard-user text-omg-kashmir fa-2x mb-3"></i>
                        <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
                        <p class="text-xs font-body text-omg-kashmir mt-1">
                            Crea tu primera aula con el botón de arriba
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection