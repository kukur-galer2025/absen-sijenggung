<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased relative">
        <!-- Background Image -->
        <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/bg-desa.png') }}');"></div>
        <div class="fixed inset-0 z-[-1] bg-black opacity-30"></div> <!-- Overlay for text readability -->

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-24 h-24 fill-current text-emerald-400 drop-shadow-md" />
                    <h1 class="mt-4 text-3xl font-extrabold text-white tracking-wider drop-shadow-lg text-center">
                        BALAI DESA SIJENGGUNG
                    </h1>
                </a>
            </div>

            <div class="w-11/12 sm:w-full sm:max-w-md mt-8 px-6 py-8 sm:px-8 sm:py-10 bg-white/80 backdrop-blur-md shadow-2xl overflow-hidden rounded-2xl border border-white/40">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
