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

        <!-- Custom CSS for Auth Pages (optional, for extra styling) -->
        <style>
            body {
                background-color: #f8f9fa; /* Light gray background */
            }
            .auth-card {
                max-width: 400px;
                margin: 50px auto;
                padding: 2rem;
                border-radius: 0.5rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                background-color: #fff;
            }
            .auth-card-header {
                text-align: center;
                margin-bottom: 1.5rem;
            }
            .auth-card-header img {
                width: 80px; /* Adjust logo size */
                height: 80px;
                margin-bottom: 1rem;
            }
            .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            }
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }
            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }
            .btn-link {
                color: #007bff;
            }
            .btn-link:hover {
                color: #0056b3;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
            {{ $slot }}
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
