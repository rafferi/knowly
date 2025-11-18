<nav class="navbar">
    <div class="nav-container">
        <a href="/" class="nav-logo">
            <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
            <span class="logo-text">Knowly</span>
        </a>

        <ul class="nav-menu">
            <li><a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Главная</a></li>
            <li><a href="/courses" class="nav-link {{ request()->is('courses') ? 'active' : '' }}">Курсы</a></li>
            <li><a href="/about" class="nav-link {{ request()->is('about') ? 'active' : '' }}">О нас</a></li>

            @auth
                <li><a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">Кабинет</a></li>
                <li><a href="/profile" class="nav-link {{ request()->is('profile') ? 'active' : '' }}">Профиль</a></li>
            @else
                <li><a href="#reviews" class="nav-link">Отзывы</a></li>
                <li><a href="#contact" class="nav-link">Контакты</a></li>
            @endauth
        </ul>

        <div class="nav-auth">
            @auth
                @if(Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="Аватар" class="nav-avatar">
                @else
                    <i class="fas fa-user nav-avatar-icon"></i>
                @endif
                <span class="user-greeting">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-login">Выход</button>
                </form>
            @else
                <a href="/login" class="btn-login">Вход</a>
                <a href="/signup" class="btn-signup">Начать обучение</a>
            @endauth
        </div>

        <div class="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<div class="mobile-nav">
    <ul class="mobile-nav-menu">
        <li><a href="/" class="mobile-nav-link {{ request()->is('/') ? 'active' : '' }}">Главная</a></li>
        <li><a href="/courses" class="mobile-nav-link {{ request()->is('courses') ? 'active' : '' }}">Курсы</a></li>
        <li><a href="/about" class="mobile-nav-link {{ request()->is('about') ? 'active' : '' }}">О нас</a></li>

        @auth
            <li><a href="/dashboard" class="mobile-nav-link {{ request()->is('dashboard') ? 'active' : '' }}">Кабинет</a></li>
            <li><a href="/profile" class="mobile-nav-link {{ request()->is('profile') ? 'active' : '' }}">Профиль</a></li>
        @else
            <li><a href="#reviews" class="mobile-nav-link">Отзывы</a></li>
            <li><a href="#contact" class="mobile-nav-link">Контакты</a></li>
        @endauth
    </ul>

    <div class="mobile-nav-auth">
        @auth
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-login">Выход</button>
            </form>
        @else
            <a href="/login" class="btn-login">Вход</a>
            <a href="/signup" class="btn-signup">Начать обучение</a>
        @endauth
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const mobileNav = document.querySelector('.mobile-nav');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                this.classList.toggle('active');
                mobileNav.classList.toggle('active');
                document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
            });
        }

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuBtn.classList.remove('active');
                mobileNav.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        @guest
        if (window.location.pathname === '/') {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }
        @endguest
    });
</script>
