{{-- Mensajes de sesión --}}
@if (session('success'))
    <div class="mx-6 mt-4 flex items-center gap-3 bg-white border border-green-200 rounded-lg px-4 py-3">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        <p class="text-sm text-omg-dark">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mx-6 mt-4 flex items-center gap-3 bg-white border border-red-200 rounded-lg px-4 py-3">
        <i class="fa-solid fa-circle-xmark text-red-500"></i>
        <p class="text-sm text-omg-dark">{{ session('error') }}</p>
    </div>
@endif

@if (session('warning'))
    <div class="mx-6 mt-4 flex items-center gap-3 bg-white border border-yellow-200 rounded-lg px-4 py-3">
        <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
        <p class="text-sm text-omg-dark">{{ session('warning') }}</p>
    </div>
@endif