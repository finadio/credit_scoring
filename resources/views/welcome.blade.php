<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Credit Scoring System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom CSS for Welcome Page -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(to right, #007bff, #0056b3); /* Professional blue gradient */
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .hero-section {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 50px 15px;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-outline-light:hover {
            background-color: #fff;
            color: #007bff !important;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent pt-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="d-inline-block align-text-top">
                <span class="fw-bold">{{ config('app.name', 'Credit Scoring') }}</span>
            </a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    <div class="d-flex">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-light btn-sm">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Sistem Penilaian Kredit Terdepan</h1>
            <p class="hero-subtitle">Membantu institusi keuangan membuat keputusan kredit yang cepat, akurat, dan terpercaya.</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-hero me-3">Mulai Sekarang</a>
            <a href="#" class="btn btn-outline-light btn-hero">Pelajari Lebih Lanjut</a>
        </div>
    </div>

    <footer class="footer">
        &copy; {{ date('Y') }} {{ config('app.name', 'Credit Scoring System') }}. All rights reserved.
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
