{{--
    @file index.blade.php
    @description Vista de suscripción del Docente
    @version 1.0.0
--}}
@extends('layouts.app')
@section('title', 'Mi Suscripción')
@section('content')

{{-- Título --}}
<div class="mb-6">
    <h1 class="text-2xl font-heading font-semibold text-omg-nile">Mi Suscripción</h1>
    <p class="text-sm font-body text-omg-kashmir mt-1">
        Gestiona tu plan y métodos de pago
    </p>
</div>

{{-- Flash: pago exitoso --}}
@if (session('pago_exitoso'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-4 mb-6">
    <i class="fa-solid fa-circle-check text-green-500 fa-lg"></i>
    <p class="text-sm font-body font-semibold text-omg-dark">{{ session('pago_exitoso') }}</p>
</div>
@endif

{{-- Flash: error de pago --}}
@if (session('pago_error'))
<div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-4 mb-6">
    <i class="fa-solid fa-circle-xmark text-red-500 fa-lg"></i>
    <p class="text-sm font-body font-semibold text-omg-dark">{{ session('pago_error') }}</p>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Plan actual --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-6">
        <h2 class="text-base font-heading font-semibold text-omg-nile mb-5">
            <i class="fa-solid fa-crown me-2 text-omg-coral"></i>
            Plan actual
        </h2>

        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center
                {{ $suscripcion['plan'] === 1 ? 'bg-omg-chardon' : 'bg-omg-nile' }}">
                <i class="fa-solid fa-crown fa-lg
                    {{ $suscripcion['plan'] === 1 ? 'text-omg-kashmir' : 'text-white' }}"></i>
            </div>
            <div>
                <p class="text-xl font-heading font-semibold text-omg-nile">
                    Plan {{ $suscripcion['plan_nombre'] }}
                </p>
                <span class="text-xs font-body px-2 py-1 rounded-full
                    {{ $suscripcion['est_suscripcion'] === 1 ? 'bg-green-100 text-green-700' :
                       ($suscripcion['est_suscripcion'] === 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                    {{ $suscripcion['est_nombre'] }}
                </span>
            </div>
        </div>

        <div class="space-y-3 mb-6">
            <div class="flex items-center justify-between py-2 border-b border-omg-pastel">
                <span class="text-sm font-body text-omg-kashmir">Fecha de inicio</span>
                <span class="text-sm font-body text-omg-dark">
                    {{ $suscripcion['fec_inicio'] ?? '—' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-omg-pastel">
                <span class="text-sm font-body text-omg-kashmir">Fecha de vencimiento</span>
                <span class="text-sm font-body text-omg-dark">
                    {{ $suscripcion['plan'] === 1 ? 'No vence' : ($suscripcion['fec_fin'] ?? '—') }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-sm font-body text-omg-kashmir">Último pago</span>
                <span class="text-sm font-body text-omg-dark">
                    {{ $suscripcion['fec_ultimo_pago'] ?? 'Sin pagos' }}
                </span>
            </div>
        </div>

        {{-- Comparación de planes --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Plan Básico --}}
            <div class="border rounded-xl p-4
                {{ $suscripcion['plan'] === 1 ? 'border-omg-coral bg-omg-chardon' : 'border-omg-kashmir-dark' }}">
                <p class="text-sm font-heading font-semibold text-omg-nile mb-2">Básico</p>
                <p class="text-lg font-heading font-semibold text-omg-nile">Gratis</p>
                <ul class="mt-3 space-y-1">
                    <li class="text-xs font-body text-omg-kashmir flex items-center gap-1">
                        <i class="fa-solid fa-check text-green-500"></i> Grupos ilimitados
                    </li>
                    <li class="text-xs font-body text-omg-kashmir flex items-center gap-1">
                        <i class="fa-solid fa-check text-green-500"></i> Sesiones ilimitadas
                    </li>
                    <li class="text-xs font-body text-omg-kashmir flex items-center gap-1">
                        <i class="fa-solid fa-check text-green-500"></i> Reportes básicos
                    </li>
                </ul>
            </div>

            {{-- Plan Mensual --}}
            <div class="border rounded-xl p-4
                {{ $suscripcion['plan'] === 2 ? 'border-omg-coral bg-omg-chardon' : 'border-omg-kashmir-dark' }}">
                <p class="text-sm font-heading font-semibold text-omg-nile mb-2">Mensual</p>
                <p class="text-lg font-heading font-semibold text-omg-nile">$149 <span class="text-xs font-body text-omg-kashmir">MXN/mes</span></p>
                <ul class="mt-3 space-y-1">
                    <li class="text-xs font-body text-omg-kashmir flex items-center gap-1">
                        <i class="fa-solid fa-check text-green-500"></i> Todo lo del básico
                    </li>
                    <li class="text-xs font-body text-omg-kashmir flex items-center gap-1">
                        <i class="fa-solid fa-check text-green-500"></i> Exportar reportes
                    </li>
                    <li class="text-xs font-body text-omg-kashmir flex items-center gap-1">
                        <i class="fa-solid fa-check text-green-500"></i> Soporte prioritario
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Pago --}}
    <div class="bg-white rounded-xl border border-omg-kashmir-dark p-6">
        <h2 class="text-base font-heading font-semibold text-omg-nile mb-5">
            <i class="fa-brands fa-paypal me-2 text-omg-nile"></i>
            Actualizar plan
        </h2>

        @if ($suscripcion['plan'] === 2 && $suscripcion['est_suscripcion'] === 1)
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-4 mb-6">
                <i class="fa-solid fa-circle-check text-green-500 fa-lg"></i>
                <div>
                    <p class="text-sm font-body font-semibold text-omg-dark">Plan Mensual activo</p>
                    <p class="text-xs font-body text-omg-kashmir mt-0.5">
                        Tu plan vence el {{ $suscripcion['fec_fin'] }}
                    </p>
                </div>
            </div>
        @endif

        @if ($suscripcion['plan'] === 1 || $suscripcion['est_suscripcion'] === 2)
            <div class="bg-omg-chardon rounded-xl p-5 mb-6">
                <p class="text-sm font-body text-omg-dark mb-1">
                    Actualiza al <strong>Plan Mensual</strong> por solo
                </p>
                <p class="text-3xl font-heading font-semibold text-omg-nile">
                    $149 <span class="text-base font-body text-omg-kashmir">MXN/mes</span>
                </p>
            </div>

            {{-- Botón PayPal --}}
            <div id="paypal-container">
                <button id="btn-paypal"
                    onclick="iniciarPago()"
                    class="w-full flex items-center justify-center gap-2 bg-omg-coral hover:bg-omg-coral-dark text-white font-heading font-semibold py-3 rounded-lg transition-colors text-sm">
                    <i class="fa-brands fa-paypal"></i>
                    Pagar con PayPal
                </button>
            </div>

            <div id="paypal-loading" class="hidden text-center py-4">
                <i class="fa-solid fa-spinner fa-spin text-omg-nile fa-lg"></i>
                <p class="text-sm font-body text-omg-kashmir mt-2">Conectando con PayPal...</p>
            </div>

            <div id="paypal-error" class="hidden flex items-center gap-3 bg-white border border-red-200 rounded-lg px-4 py-3 mt-4">
                <i class="fa-solid fa-circle-xmark text-red-500"></i>
                <p class="text-sm font-body text-omg-dark" id="paypal-error-msg"></p>
            </div>
        @endif

        {{-- Información de pago seguro --}}
        <div class="flex items-center gap-2 mt-6">
            <i class="fa-solid fa-lock text-omg-kashmir text-xs"></i>
            <p class="text-xs font-body text-omg-kashmir">
                Pago seguro procesado por PayPal
            </p>
        </div>
    </div>

</div>

@push('scripts')
<script>
async function iniciarPago() {
    const btn = document.getElementById('btn-paypal');
    const loading = document.getElementById('paypal-loading');
    const errorDiv = document.getElementById('paypal-error');
    const errorMsg = document.getElementById('paypal-error-msg');

    btn.classList.add('hidden');
    loading.classList.remove('hidden');
    errorDiv.classList.add('hidden');

    try {
        const response = await fetch('{{ route('ca.suscripcion.crear-orden') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await response.json();

        if (data.data?.approve_url) {
            window.location.href = data.data.approve_url;
        } else {
            throw new Error('No se pudo obtener el enlace de pago');
        }
    } catch (error) {
        loading.classList.add('hidden');
        btn.classList.remove('hidden');
        errorDiv.classList.remove('hidden');
        errorMsg.textContent = 'No fue posible completar la operación';
    }
}
</script>
@endpush

@endsection