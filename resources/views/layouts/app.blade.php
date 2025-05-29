<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- 2) CSS của DataTables -->
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css"
    />

    <!-- 3) DataTables JS (sau jQuery) -->
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script src="{{ asset('js/dataTables.js')  }}"></script>

    {{-- css tự custom--}}
    <link rel="stylesheet" href="{{ asset('css/custom-datatable.css') }}">

    {{-- js model--}}
    <script src="{{ asset('js/model-employee.js') }}"></script>
    <script src="{{ asset('js/model-shift.js') }}"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Success popup -->
    <x-success-model/>

</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @auth
        @if(auth()->user()->role === 'manager')
            @include('layouts.manager-navigation')
        @else
            {{-- Nếu muốn hiển thị navigation khác cho user non-manager --}}
            @include('layouts.employee-navigation')

            {{--                    @include('layouts.navigation-guest')--}}
        @endif
    @endauth
    <!-- Bao gồm Modal -->
    <x-user-model/>

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
</body>
</html>
