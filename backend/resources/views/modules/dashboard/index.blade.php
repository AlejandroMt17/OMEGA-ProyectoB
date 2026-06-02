{{--
    @file index.blade.php
    @description Dashboard principal del Docente
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Título --}}
<div class="mb-6">
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">Dashboard</h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->ap_pat }}
    </p>
</div>

{{-- Tarjetas de resumen --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Aulas activas --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-omg-chardon rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-chalkboard-user text-omg-nile fa-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-heading font-semibold text-omg-nile">{{ $aulasActivas }}</p>
            <p class="text-xs font-body text-omg-kashmir">Aulas activas</p>
        </div>
    </div>

    {{-- Sesiones del día --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-omg-chardon rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-calendar-day text-omg-nile fa-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-heading font-semibold text-omg-nile">{{ $sesionesHoy }}</p>
            <p class="text-xs font-body text-omg-kashmir">Sesiones hoy</p>
        </div>
    </div>

    {{-- Alumnos en riesgo --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-omg-chardon rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-triangle-exclamation text-omg-coral fa-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-heading font-semibold text-omg-nile">0</p>
            <p class="text-xs font-body text-omg-kashmir">Alumnos en riesgo</p>
        </div>
    </div>

    {{-- Justificantes pendientes --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-omg-chardon rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-file-circle-check text-omg-nile fa-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-heading font-semibold text-omg-nile">0</p>
            <p class="text-xs font-body text-omg-kashmir">Justificantes pendientes</p>
        </div>
    </div>

</div>

{{-- Sección inferior --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Mis aulas --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-heading font-semibold text-omg-nile">Mis Aulas</h2>
            <a href="{{ route('ca.grupos.index') }}"
               class="text-xs font-body text-omg-nile-light hover:underline">
                Ver todas
            </a>
        </div>
        @if($aulasActivas === 0)
            <div class="text-center py-8">
                <i class="fa-solid fa-chalkboard-user text-omg-kashmir fa-2x mb-3"></i>
                <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
                <p class="text-xs font-body text-omg-kashmir mt-1">
                    Crea tu primera aula desde Mis Aulas
                </p>
            </div>
        @else
            <p class="text-sm font-body text-omg-kashmir">
                Tienes {{ $aulasActivas }} aula(s) registrada(s)
            </p>
        @endif
    </div>

    {{-- Sesiones recientes --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-heading font-semibold text-omg-nile">Sesiones de Hoy</h2>
        </div>
        @if($sesionesHoy === 0)
            <div class="text-center py-8">
                <i class="fa-solid fa-calendar-day text-omg-kashmir fa-2x mb-3"></i>
                <p class="text-sm font-body text-omg-kashmir">No se encontraron registros</p>
                <p class="text-xs font-body text-omg-kashmir mt-1">
                    No hay sesiones registradas para hoy
                </p>
            </div>
        @else
            <p class="text-sm font-body text-omg-kashmir">
                Tienes {{ $sesionesHoy }} sesión(es) hoy
            </p>
        @endif
    </div>

</div>

@endsection