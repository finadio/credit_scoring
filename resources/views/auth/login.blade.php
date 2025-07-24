<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="d-block mx-auto mb-3">
            </a>
            <h2 class="h4 text-center">Login ke Akun Anda</h2>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label" for="remember_me">Ingat Saya</label>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif

                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>
            <div class="mt-3 text-center">
                Belum punya akun? <a href="{{ route('register') }}" class="btn btn-link p-0">Daftar Sekarang</a>
            </div>
        </form>
    </div>
</x-guest-layout>
