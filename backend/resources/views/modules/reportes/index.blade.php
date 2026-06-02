{{--
    @file index.blade.php
    @description Reportes generales de asistencia por grupo
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Reportes')
@section('content')

{{-- Título --}}
<div class="mb-6">
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">Reportes</h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        Visualiza el resumen de asistencias por grupo
    </p>
</div>

{{-- Tarjetas por grupo --}}
@forelse ($reportes as $reporte)
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-heading font-semibold text-omg-nile">
                    {{ $reporte['grupo']->nombre }} — {{ $reporte['grupo']->materia }}
                </h2>
                <p class="text-xs font-body text-omg-kashmir mt-0.5">
                    {{ $reporte['grupo']->periodo }} ·
                    {{ $reporte['total_sesiones'] }} sesión(es)
                </p>
            </div>
            <a href="{{ route('ca.reportes.detalle', $reporte['grupo']->id_grupo) }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-omg-pastel hover:bg-omg-nile hover:text-white text-omg-nile rounded-lg text-xs font-body transition-colors">
                <i class="fa-solid fa-ellipsis"></i>
                Detalles
            </a>
        </div>

        {{-- Barra de progreso --}}
        <div class="mb-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-body text-omg-kashmir">Porcentaje de asistencia</span>
                <span class="text-sm font-heading font-semibold text-omg-nile">
                    {{ $reporte['porcentaje'] }}%
                </span>
            </div>
            <div class="w-full bg-omg-pastel rounded-full h-2">
                <div class="h-2 rounded-full transition-all
                    {{ $reporte['porcentaje'] >= 80 ? 'bg-green-500' : ($reporte['porcentaje'] >= 60 ? 'bg-yellow-400' : 'bg-red-500') }}"
                    style="width: {{ $reporte['porcentaje'] }}%">
                </div>
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-lg font-heading font-semibold text-green-600">
                    {{ $reporte['total_presentes'] }}
                </p>
                <p class="text-xs font-body text-omg-kashmir">Presentes</p>
            </div>
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <p class="text-lg font-heading font-semibold text-red-500">
                    {{ $reporte['total_ausentes'] }}
                </p>
                <p class="text-xs font-body text-omg-kashmir">Ausentes</p>
            </div>
            <div class="bg-omg-chardon rounded-lg p-3 text-center">
                <p class="text-lg font-heading font-semibold text-omg-nile">
                    {{ $reporte['total_justif'] }}
                </p>
                <p class="text-xs font-body text-omg-kashmir">Justificadas</p>
            </div>
        </div>
    </div>
@empty
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-12 text-center">
        <i class="fa-solid fa-chart-bar text-omg-kashmir fa-2x mb-3"></i>
        <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
        <p class="text-xs font-body text-omg-kashmir mt-1">
            Crea grupos y registra sesiones para ver reportes
        </p>
    </div>
@endforelse

@endsection