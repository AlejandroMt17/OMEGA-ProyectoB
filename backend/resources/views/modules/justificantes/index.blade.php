{{--
    @file index.blade.php
    @description Gestión de justificantes de asistencia
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Justificantes')
@section('content')

{{-- Título --}}
<div class="mb-6">
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">Justificantes</h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        Gestiona las ausencias y justificantes de tus alumnos
    </p>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-omg-kashmir-dark overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-omg-kashmir-dark bg-omg-chardon">
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Alumno</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Grupo</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Sesión</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Estado</th>
                <th class="text-right px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-omg-kashmir-dark">
            @forelse ($ausencias as $asistencia)
                <tr class="hover:bg-omg-chardon transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-body font-semibold text-omg-dark">
                            {{ $asistencia->alumno->nombre }}
                            {{ $asistencia->alumno->ap_pat }}
                        </p>
                        <p class="text-xs font-body text-omg-kashmir">
                            {{ $asistencia->alumno->email }}
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-body text-omg-dark">
                            {{ $asistencia->sesion->grupo->nombre }}
                        </p>
                        <p class="text-xs font-body text-omg-kashmir">
                            {{ $asistencia->sesion->grupo->materia }}
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-body text-omg-dark">
                            {{ $asistencia->sesion->fec_sesion->format('d/m/Y') }}
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        @if ($asistencia->est_asistencia === 2)
                            <span class="bg-red-100 text-red-600 text-xs font-body px-2 py-1 rounded-full">
                                Ausente
                            </span>
                        @else
                            <span class="bg-omg-pastel text-omg-nile text-xs font-body px-2 py-1 rounded-full">
                                Justificada
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            @if ($asistencia->est_asistencia === 2)
                                <form method="POST"
                                      action="{{ route('ca.justificantes.justificar', $asistencia->id_asistencia) }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-omg-nile hover:bg-omg-nile-dark text-white rounded-lg text-xs font-body transition-colors">
                                        <i class="fa-solid fa-file-circle-check"></i>
                                        Justificar
                                    </button>
                                </form>
                            @else
                                <form method="POST"
                                      action="{{ route('ca.justificantes.ausente', $asistencia->id_asistencia) }}"
                                      onsubmit="return confirm('Esta acción no se puede deshacer')">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-500 hover:text-white text-red-500 rounded-lg text-xs font-body transition-colors">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                        Marcar ausente
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center">
                        <i class="fa-solid fa-file-circle-check text-omg-kashmir fa-2x mb-3"></i>
                        <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
                        <p class="text-xs font-body text-omg-kashmir mt-1">
                            No hay ausencias registradas en tus grupos
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection