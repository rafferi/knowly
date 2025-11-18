@extends('app')

@section('title', 'Регистрация - Knowly')

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
                <h1 class="auth-title">Начните учиться!</h1>
                <p class="auth-subtitle">Создайте аккаунт для доступа ко всем курсам</p>
            </div>

            <!-- Кнопка для прохождения теста -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="/course-recommendation-test" class="btn" style="background: var(--light-blue); color: white; padding: 0.8rem 1.5rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; transition: all 0.3s ease;">
                    <i class="fas fa-search"></i>
                    Не знаете какой курс выбрать? Пройдите тест!
                </a>
            </div>

            @if($errors->any())
                <div style="background: #ffe6e6; color: #d00; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #ffcccc;">
                    @foreach($errors->all() as $error)
                        <p style="margin: 0; font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div style="background: #e6ffe6; color: #008000; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #ccffcc;">
                    <p style="margin: 0; font-size: 0.9rem;">{{ session('success') }}</p>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Ваше имя" value="{{ old('name') }}" required>
                    <i class="fas fa-user form-input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com" value="{{ old('email') }}" required>
                    <i class="fas fa-envelope form-input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Создайте пароль" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Повторите пароль" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="terms" required>
                        Я соглашаюсь с <a href="/terms" style="color: var(--grenadine);">условиями использования</a>
                    </label>
                </div>

                <button type="submit" class="auth-button">
                    <i class="fas fa-user-plus"></i> Создать аккаунт
                </button>
            </form>

            <div class="auth-divider">
                <span>Или зарегистрируйтесь через</span>
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
                Уже есть аккаунт?
                <a href="/login" class="auth-link">Войти</a>
            </div>

            <div class="auth-features">
                <div class="auth-feature">
                    <i class="fas fa-rocket"></i>
                    <span>Первый урок бесплатно</span>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-chart-line"></i>
                    <span>Персональная программа обучения</span>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Сертификат по окончании курса</span>
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

        // Валидация формы
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');

            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Пароли не совпадают!');
                confirmPassword.focus();
            }

            if (password.value.length < 8) {
                e.preventDefault();
                alert('Пароль должен содержать минимум 8 символов!');
                password.focus();
            }
        });
    </script>
@endsection
