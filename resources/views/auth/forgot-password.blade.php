<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="d-block mx-auto mb-3">
            </a>
            <h2 class="h4 text-center">Lupa Password</h2>
        </div>

        <div class="mb-4 text-muted">
            {{ __('Lupa password Anda? Tidak masalah. Cukup berikan alamat email Anda dan kami akan mengirimkan tautan reset password yang memungkinkan Anda memilih yang baru.') }}
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    Kirim Tautan Reset Password
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
