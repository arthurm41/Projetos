<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- Token CSRF --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Título da aba: usa o nome do app --}}
        <title>{{ config('app.name', 'SenaiStock') }}</title>

        {{-- Fonte Figtree --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        {{-- Tailwind CSS com configuração da fonte customizada --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Figtree', 'sans-serif'] }
                    }
                }
            }
        </script>
    </head>
    {{-- Layout minimalista sem navegação — usado pelas páginas de autenticação (login, registro) --}}
    <body class="font-sans antialiased bg-white">
        {{ $slot }}
    </body>
</html>
