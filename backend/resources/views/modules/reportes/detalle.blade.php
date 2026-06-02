{{--
    @file detalle.blade.php
    @description Reporte detallado de asistencia por sesión
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Reporte — ' . $grupo->nombre)
@section('content')

{{-- Breadcrumb y título --}}
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('ca.reportes.index') }}"
           class="text-sm font-body text-omg-nile-light hover:underline">Reportes</a>
        <i class="fa-solid fa-chevron-right text-omg-kashmir text-xs"></i>
        <span class="text-sm font-body text-omg-dark">{{ $grupo->nombre }}</span>
    </div>
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">
        Reporte — {{ $grupo->nombre }}
    </h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        {{ $grupo->materia }} · {{ $grupo->periodo }}
    </p>
</div>

{{-- Tabla de sesiones --}}
<div class="bg-white rounded-xl border border-omg-kashmir-dark overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-omg-kashmir-dark bg-omg-chardon">
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Fecha</th>
                <th class="text-left px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Estado</th>
                <th class="text-center px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Presentes</th>
                <th class="text-center px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Ausentes</th>
                <th class="text-center px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Justificadas</th>
                <th class="text-right px-5 py-3 text-xs font-heading font-semibold text-omg-nile uppercase tracking-wide">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-omg-kashmir-dark">
            @forelse ($sesiones as $item)
                <tr class="hover:bg-omg-chardon transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-body text-omg-dark">
                            {{ $item['sesion']->fec_sesion->format('d/m/Y') }}
                        </p>
                        <p class="text-xs font-body text-omg-kashmir">
                            {{ $item['sesion']->hora_apertura->format('H:i') }}
                            @if($item['sesion']->hora_cierre)
                                — {{ $item['sesion']->hora_cierre->format('H:i') }}
                            @endif
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        @if ($item['sesion']->est_sesion === 1)
                            <span class="bg-green-100 text-green-700 text-xs font-body px-2 py-1 rounded-full">
                                Activa
                            </span>
                        @else
                            <span class="bg-omg-pastel text-omg-kashmir text-xs font-body px-2 py-1 rounded-full">
                                Cerrada
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-sm font-heading font-semibold text-green-600">
                            {{ $item['presentes'] }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-sm font-heading font-semibold text-red-500">
                            {{ $item['ausentes'] }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-sm font-heading font-semibold text-omg-nile">
                            {{ $item['justif'] }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end">
                            <a href="{{ route('ca.sesiones.asistencias', $item['sesion']->id_sesion) }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 bg-omg-pastel hover:bg-omg-nile hover:text-white text-omg-nile rounded-lg text-xs font-body transition-colors">
                                <i class="fa-solid fa-ellipsis"></i>
                                Detalles
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fa-solid fa-chart-bar text-omg-kashmir fa-2x mb-3"></i>
                        <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
                        <p class="text-xs font-body text-omg-kashmir mt-1">
                            No hay sesiones registradas para este grupo
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection