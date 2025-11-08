@extends('app')

@section('title', 'Вход - Knowly')

@section('content')
    <!-- Основной контент -->
    <div class="auth-container">
        <div class="auth-background">
            <div class="auth-bg-shape auth-shape-1"></div>
            <div class="auth-bg-shape auth-shape-2"></div>
            <div class="auth-bg-shape auth-shape-3"></div>
        </div>

        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <div class="auth-logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="auth-logo-text">Knowly</span>
                </div>
                <h1 class="auth-title">С возвращением!</h1>
                <p class="auth-subtitle">Войдите в свой аккаунт чтобы продолжить обучение</p>
            </div>

            @if($errors->any())
                <div style="background: #ffe6e6; color: #d00; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #ffcccc;">
                    @foreach($errors->all() as $error)
                        <p style="margin: 0; font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('status'))
                <div style="background: #e6ffe6; color: #008000; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #ccffcc;">
                    <p style="margin: 0; font-size: 0.9rem;">{{ session('status') }}</p>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com" value="{{ old('email') }}" required>
                    <i class="fas fa-envelope form-input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Введите ваш пароль" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" checked>
                        <span class="checkmark"></span>
                        Запомнить меня на этом устройстве
                    </label>
                    <a href="/forgot-password" class="forgot-password">Забыли пароль?</a>
                </div>

                <button type="submit" class="auth-button">
                    <i class="fas fa-sign-in-alt"></i> Войти в аккаунт
                </button>
            </form>

            <div class="auth-divider">
                <span>Или войдите через</span>
            </div>

            <div class="social-auth">
                <a href="#" class="social-button vk">
                    <i class="fab fa-vk"></i> VK
                </a>
                <a href="#" class="social-button telegram">
                    <i class="fab fa-telegram-plane"></i> Telegram
                </a>
            </div>

            <div class="auth-footer">
                Ещё нет аккаунта?
                <a href="/signup" class="auth-link">Зарегистрироваться</a>
            </div>

            <div class="auth-features">
                <div class="auth-feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Безопасность данных гарантирована</span>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-clock"></i>
                    <span>Доступ к курсам 24/7</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Переключение видимости пароля
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
@endsection
