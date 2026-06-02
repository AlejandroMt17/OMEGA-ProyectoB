{{--
    @file app.blade.php
    @description Layout principal post-login
    @version 1.0.0
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Control de Asistencias')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-omg-white min-h-screen flex">

    @include('partials.sidebar')

    <div class="flex-1 flex flex-col min-h-screen ml-64">
        @include('partials.header')
        @include('partials.session-messages')
        <main class="flex-1 p-6">
            @yield('content')
        </main>
        @include('partials.footer')
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>