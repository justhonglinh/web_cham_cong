<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background-color: #eee;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-tr from-blue-100 via-blue-50 to-blue-200 dark:from-blue-900 dark:via-blue-800 dark:to-blue-900">
<section class="min-h-screen flex items-center justify-center py-10 px-4">
    <div class="flex flex-col md:flex-row items-center justify-center max-w-6xl w-full gap-8">
        <!-- Image -->
        <div class="md:w-1/2 max-w-lg">
            <img
                src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                alt="Login Illustration"
                class="w-full h-auto"
                loading="lazy"
            />
        </div>

        <!-- Content -->
        <div class="md:w-1/2 flex flex-col items-center w-full max-w-md space-y-6">
            <a href="/" aria-label="Home">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-gray-400" />
            </a>

            <div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden px-6 py-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
</body>
</html>
