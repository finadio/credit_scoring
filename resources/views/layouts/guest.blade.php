<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Credit Scoring System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Custom CSS for Auth Pages -->
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(to right, #007bff, #0056b3); /* Professional blue gradient */
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .auth-card {
                max-width: 420px; /* Slightly wider */
                width: 100%;
                padding: 2.5rem;
                border-radius: 0.75rem; /* More rounded corners */
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Stronger shadow */
                background-color: #fff;
                animation: fadeIn 0.8s ease-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .auth-card-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            .auth-card-header img {
                width: 90px; /* Larger logo */
                height: 90px;
                margin-bottom: 1.5rem;
                border-radius: 50%; /* Make logo circular if desired */
                box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.2); /* Subtle glow */
            }
            .auth-card-header h2 {
                color: #343a40; /* Darker text for headings */
                font-weight: 600;
            }
            .form-label {
                font-weight: 500;
                color: #495057;
            }
            .form-control {
                border-radius: 0.3rem;
                border-color: #ced4da;
            }
            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            }
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
                border-radius: 0.3rem;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
                transform: translateY(-2px);
            }
            .btn-link {
                color: #007bff;
                text-decoration: none;
                font-weight: 500;
            }
            .btn-link:hover {
                color: #0056b3;
                text-decoration: underline;
            }
            .alert {
                border-radius: 0.3rem;
            }
        </style>
    </head>
    <body>
        <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
            {{ $slot }}
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
