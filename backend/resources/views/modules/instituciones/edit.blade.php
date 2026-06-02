{{--
    @file edit.blade.php
    @description Formulario para editar una institución
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Editar Institución')
@section('content')

{{-- Título --}}
<div class="mb-6">
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">Editar Institución</h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        Modifica la información de {{ $institucion->nombre }}
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

    <form method="POST"
          action="{{ route('ca.instituciones.update', $institucion->id_institucion) }}"
          class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                Nombre de la institución
            </label>
            <input
                type="text"
                name="nombre"
                value="{{ old('nombre', $institucion->nombre) }}"
                required
                placeholder="Ej: Tecnológico de Toluca"
                class="w-full px-4 py-2.5 bg-white border border-omg-kashmir rounded-lg text-sm font-body text-omg-dark focus:outline-none focus:ring-2 focus:ring-omg-kashmir focus:border-transparent @error('nombre') border-red-400 @enderror"
            />
            @error('nombre')
                <p class="text-xs text-red-500 mt-1 italic font-body">{{ $message }}</p>
            @enderror
        </div>

        {{-- Logo (deshabilitado) --}}
        <div>
            <label class="block text-sm font-body text-omg-dark mb-1">
                URL del logotipo
            </label>
            <input
                type="text"
                value="{{ $institucion->logo }}"
                disabled
                class="w-full px-4 py-2.5 bg-omg-pastel border border-omg-kashmir rounded-lg text-sm font-body text-omg-kashmir cursor-not-allowed"
            />
            <p class="text-xs font-body text-omg-kashmir mt-1 italic">
                El logo no es editable
            </p>
        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('ca.instituciones.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-omg-pastel hover:bg-omg-nile hover:text-white text-omg-nile font-heading font-semibold rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-ban"></i>
                Cancelar
            </a>
            <button
                type="submit"
                class="flex items-center gap-2 px-4 py-2.5 bg-omg-coral hover:bg-omg-coral-dark text-white font-heading font-semibold rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-check"></i>
                Actualizar
            </button>
        </div>

    </form>
</div>

@endsection