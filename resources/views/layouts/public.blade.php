<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SMK Bisa - Sistem Informasi Sekolah')</title>

    <!-- Vite CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <x-public.navbar />

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-public.footer />

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col items-end"></div>

</body>
</html>
