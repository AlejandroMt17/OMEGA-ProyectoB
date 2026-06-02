{{--
    @file create.blade.php
    @description Formulario para crear un nuevo grupo/aula
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Nueva Aula')
@section('content')

{{-- Título --}}
<div class="mb-6">
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">Nueva Aula</h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        Registra un nuevo grupo para controlar asistencias
    </p>
</div>

{{-- Formulario --}}
<div class="bg-white rounded-xl border border-omg-kashmir-dark p-6 max-w-lg">

    @if ($errors->any())
        <div class="flex items-start gap-3 bg-white border border-red-200 rounded-lg px-4 py-3 mb-6">
            <i class="fa-solid fa-circle-xmark text-red-500 mt-0.5"></i>
            <ul class="text-sm text-omg-dark space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ca.grupos.store') }}" class="space-y-5">
        @csrf

        {{-- Institución --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                Institución
            </label>
            <select
                name="id_institucion"
                required
                class="w-full px-4 py-2.5 bg-white border border-omg-kashmir rounded-lg text-sm font-body text-omg-dark focus:outline-none focus:ring-2 focus:ring-omg-kashmir focus:border-transparent @error('id_institucion') border-red-400 @enderror">
                <option value="">Selecciona una institución</option>
                @foreach ($instituciones as $institucion)
                    <option value="{{ $institucion->id_institucion }}"
                        {{ old('id_institucion') == $institucion->id_institucion ? 'selected' : '' }}>
                        {{ $institucion->nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_institucion')
                <p class="text-xs text-red-500 mt-1 italic font-body">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nombre --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                Nombre del grupo
            </label>
            <input
                type="text"
                name="nombre"
                value="{{ old('nombre') }}"
                required
                placeholder="Ej: 3A"
                class="w-full px-4 py-2.5 bg-white border border-omg-kashmir rounded-lg text-sm font-body text-omg-dark focus:outline-none focus:ring-2 focus:ring-omg-kashmir focus:border-transparent @error('nombre') border-red-400 @enderror"
            />
            @error('nombre')
                <p class="text-xs text-red-500 mt-1 italic font-body">{{ $message }}</p>
            @enderror
        </div>

        {{-- Materia --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                Materia
            </label>
            <input
                type="text"
                name="materia"
                value="{{ old('materia') }}"
                required
                placeholder="Ej: Cálculo Diferencial"
                class="w-full px-4 py-2.5 bg-white border border-omg-kashmir rounded-lg text-sm font-body text-omg-dark focus:outline-none focus:ring-2 focus:ring-omg-kashmir focus:border-transparent @error('materia') border-red-400 @enderror"
            />
            @error('materia')
                <p class="text-xs text-red-500 mt-1 italic font-body">{{ $message }}</p>
            @enderror
        </div>

        {{-- Periodo --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                Periodo
            </label>
            <input
                type="text"
                name="periodo"
                value="{{ old('periodo') }}"
                required
                placeholder="Ej: Ene-Jun 2026"
                class="w-full px-4 py-2.5 bg-white border border-omg-kashmir rounded-lg text-sm font-body text-omg-dark focus:outline-none focus:ring-2 focus:ring-omg-kashmir focus:border-transparent @error('periodo') border-red-400 @enderror"
            />
            @error('periodo')
                <p class="text-xs text-red-500 mt-1 italic font-body">{{ $message }}</p>
            @enderror
        </div>

        {{-- No. Alumnos --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                Número de alumnos
            </label>
            <input
                type="number"
                name="no_alumnos"
                value="{{ old('no_alumnos') }}"
                required
                min="1"
                placeholder="Ej: 30"
                class="w-full px-4 py-2.5 bg-white border border-omg-kashmir rounded-lg text-sm font-body text-omg-dark focus:outline-none focus:ring-2 focus:ring-omg-kashmir focus:border-transparent @error('no_alumnos') border-red-400 @enderror"
            />
            @error('no_alumnos')
                <p class="text-xs text-red-500 mt-1 italic font-body">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('ca.grupos.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-omg-pastel hover:bg-omg-nile hover:text-white text-omg-nile font-heading font-semibold rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-ban"></i>
                Cancelar
            </a>
            <button
                type="submit"
                class="flex items-center gap-2 px-4 py-2.5 bg-omg-coral hover:bg-omg-coral-dark text-white font-heading font-semibold rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-check"></i>
                Guardar
            </button>
        </div>

    </form>
</div>

@endsection