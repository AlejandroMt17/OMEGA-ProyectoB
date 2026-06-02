{{-- Sidebar de navegación principal --}}
<aside class="fixed left-0 top-0 h-full w-64 bg-omg-nile flex flex-col z-50">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-omg-nile-light">
        <div class="w-9 h-9 bg-omg-coral rounded-lg flex items-center justify-center">
            <span class="text-white font-heading font-semibold text-sm">CA</span>
        </div>
        <div>
            <p class="text-omg-white font-heading font-semibold text-sm leading-tight">Control de</p>
            <p class="text-omg-white font-heading font-semibold text-sm leading-tight">Asistencias</p>
        </div>
    </div>

    {{-- Institución activa --}}
    @auth
    <div class="px-6 py-3 bg-omg-nile-dark">
        <p class="text-omg-kashmir text-xs">Institución activa</p>
        <p class="text-omg-white text-sm font-semibold truncate">
            {{ session('institucion_nombre', 'Sin institución') }}
        </p>
    </div>
    @endauth

    {{-- Navegación --}}
    <nav class="flex-1 px-4 py-4 overflow-y-auto">
        <ul class="space-y-1">

            <li>
                <a href="{{ route('ca.dashboard.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                   {{ request()->routeIs('ca.dashboard.*') ? 'bg-omg-coral text-white' : 'text-omg-kashmir hover:bg-omg-nile-dark hover:text-omg-white' }}">
                    <i class="fa-solid fa-house-chimney w-4"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('ca.instituciones.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                   {{ request()->routeIs('ca.instituciones.*') ? 'bg-omg-coral text-white' : 'text-omg-kashmir hover:bg-omg-nile-dark hover:text-omg-white' }}">
                    <i class="fa-solid fa-building-columns w-4"></i>
                    <span>Mis Instituciones</span>
                </a>
            </li>

            <li>
                <a href="{{ route('ca.grupos.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                   {{ request()->routeIs('ca.grupos.*') ? 'bg-omg-coral text-white' : 'text-omg-kashmir hover:bg-omg-nile-dark hover:text-omg-white' }}">
                    <i class="fa-solid fa-chalkboard-user w-4"></i>
                    <span>Mis Aulas</span>
                </a>
            </li>

            <li>
                <a href="{{ route('ca.justificantes.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                   {{ request()->routeIs('ca.justificantes.*') ? 'bg-omg-coral text-white' : 'text-omg-kashmir hover:bg-omg-nile-dark hover:text-omg-white' }}">
                    <i class="fa-solid fa-file-circle-check w-4"></i>
                    <span>Justificantes</span>
                </a>
            </li>

            <li>
                <a href="{{ route('ca.reportes.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                   {{ request()->routeIs('ca.reportes.*') ? 'bg-omg-coral text-white' : 'text-omg-kashmir hover:bg-omg-nile-dark hover:text-omg-white' }}">
                    <i class="fa-solid fa-chart-bar w-4"></i>
                    <span>Reportes</span>
                </a>
            </li>

            <li>
                <a href="{{ route('ca.suscripcion.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                   {{ request()->routeIs('ca.suscripcion.*') ? 'bg-omg-coral text-white' : 'text-omg-kashmir hover:bg-omg-nile-dark hover:text-omg-white' }}">
                    <i class="fa-solid fa-crown w-4"></i>
                    <span>Mi Suscripción</span>
                </a>
            </li>

        </ul>
    </nav>

    {{-- Usuario y logout --}}
    @auth
    <div class="px-4 py-4 border-t border-omg-nile-light">
        <a href="{{ route('ca.perfil.index') }}"
        class="flex items-center gap-3 mb-3 px-3 py-2 rounded-lg hover:bg-omg-nile-dark transition-colors">
            <div class="w-8 h-8 bg-omg-coral rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-semibold">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}{{ strtoupper(substr(auth()->user()->ap_pat, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-omg-white text-xs font-semibold truncate">
                    {{ auth()->user()->nombre }} {{ auth()->user()->ap_pat }}
                </p>
                <p class="text-omg-kashmir text-xs truncate">{{ auth()->user()->email }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('ca.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-omg-kashmir hover:bg-omg-nile-dark hover:text-red-400 transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
    @endauth

</aside>
