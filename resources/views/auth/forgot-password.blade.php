<x-guest-layout>
    <!-- Card Title -->
    <h2 class="auth-card-title">Lupa Password?</h2>
    <p class="auth-card-subtitle">Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>

    @if (session('status'))
        <div class="auth-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="auth-form-group">
            <label for="email" class="auth-label">Email</label>
            <div class="input-icon-wrapper">
                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <input 
                    id="email" 
                    class="auth-input" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    placeholder="Masukkan email anda"
                >
            </div>
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Actions -->
        <div style="margin-top: 1.5rem;">
            <button type="submit" class="auth-btn auth-btn-full">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                Kirim Link Reset Password
            </button>
        </div>

        <!-- Back to Login -->
        <div class="auth-footer">
            <p class="auth-footer-text">
                Ingat password? <a href="{{ route('login') }}">Kembali ke login</a>
            </p>
        </div>
    </form>
</x-guest-layout>
