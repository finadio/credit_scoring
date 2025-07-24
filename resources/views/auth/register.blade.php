<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="d-block mx-auto mb-3">
            </a>
            <h2 class="h4 text-center">Daftar Akun Baru</h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-3 form-check">
                    <input type="checkbox" name="terms" id="terms" required class="form-check-input">
                    <label class="form-check-label" for="terms">
                        {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-decoration-none">'.__('Ketentuan Layanan').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-decoration-none">'.__('Kebijakan Privasi').'</a>',
                        ]) !!}
                    </label>
                </div>
            @endif

            <div class="d-flex justify-content-end mt-4">
                <a class="btn btn-link me-3" href="{{ route('login') }}">
                    Sudah punya akun?
                </a>

                <button type="submit" class="btn btn-primary">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
