<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chấm Công') }}</title>

    <!-- Thêm CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Thêm jQuery (DataTables yêu cầu jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Thêm JavaScript DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    {{-- js model--}}
    <script src="{{ asset('js/model-employee.js') }}"></script>
    <script src="{{ asset('js/model-shift.js') }}"></script>
    <script src="{{ asset('js/model-overtime.js') }}"></script>
    <script src="{{ asset('js/model-attendance.js') }}"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Success popup -->
    <x-success-model/>
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @auth
        @if(auth()->user()->role === 'manager')
            @include('layouts.manager-navigation')
        @else
            @include('layouts.employee-navigation')
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
<script>
    let table = new DataTable('#Table');
</script>
</body>
</html>
