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

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Custom CSS (if needed for specific app styles not covered by Bootstrap) -->
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa; /* Light gray background */
            }
            .navbar {
                background-color: #ffffff;
                border-bottom: 1px solid #e0e0e0;
            }
            .sidebar {
                /* If you implement a sidebar, add styles here */
            }
            .content-wrapper {
                padding: 1.5rem;
            }
            .card {
                border-radius: 0.5rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            }
            .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-vh-100 bg-light">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm">
                    <div class="container py-3">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="content-wrapper">
                {{ $slot }}
            </main>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Alpine.js (Breeze uses it, so keep it) -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Custom scripts stack for charts etc. -->
        @stack('scripts')
    </body>
</html>
