<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Knowly - Платформа для изучения английского языка')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --grenadine: #DF3F32;
            --white: #FFFFFF;
            --black-beauty: #1E1E1E;
            --nimbus-cloud: #F5F5F5;
            --ultimate-gray: #939597;
            --gray-light: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            color: var(--black-beauty);
            background: var(--white);
        }

        .navbar {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--grenadine);
        }

        .nav-link {
            color: var(--black-beauty);
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--grenadine);
        }

        .btn-primary-new {
            background: var(--grenadine);
            color: var(--white);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary-new:hover {
            background: #c5372d;
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(223, 63, 50, 0.3);
        }

        .btn-secondary-new {
            background: transparent;
            color: var(--grenadine);
            border: 2px solid var(--grenadine);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-secondary-new:hover {
            background: var(--grenadine);
            color: var(--white);
            transform: translateY(-2px);
        }

        .bg-grenadine {
            background-color: var(--grenadine) !important;
        }

        .footer {
            background: var(--black-beauty);
            color: var(--white);
            padding: 3rem 0;
            margin-top: 4rem;
        }

        .footer a {
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--grenadine);
        }

        .courses-hero-new {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 6rem 0 4rem;
        }

        .courses-hero-container-new {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 3rem;
        }

        @media (min-width: 992px) {
            .courses-hero-container-new {
                flex-direction: row;
                align-items: center;
            }
        }

        .courses-hero-content-new {
            flex: 1;
        }

        .courses-hero-title-new {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--black-beauty);
            margin-bottom: 1rem;
        }

        .courses-hero-description-new {
            font-size: 1.2rem;
            color: var(--ultimate-gray);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .courses-hero-buttons-new {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .courses-hero-buttons-new {
                flex-direction: row;
            }
        }

        .levels-progress-new {
            margin-top: 2rem;
        }

        .levels-track-new {
            display: flex;
            justify-content: space-between;
            position: relative;
            padding: 1rem 0;
        }

        .levels-track-new:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--nimbus-cloud);
            transform: translateY(-50%);
            z-index: 1;
        }

        .level-marker-new {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--white);
            border: 2px solid var(--nimbus-cloud);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .level-marker-new.active-new {
            border-color: var(--grenadine);
            background: var(--grenadine);
            color: var(--white);
            transform: scale(1.1);
        }

        .level-name-new {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .courses-hero-visual-new {
            flex: 0 0 400px;
            position: relative;
        }

        .main-course-card-new {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            position: relative;
            z-index: 3;
        }

        .course-header-new {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .course-badge-new {
            background: var(--grenadine);
            color: var(--white);
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .course-level-new {
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .course-progress-new {
            margin-top: 1.5rem;
        }

        .progress-info-new {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .progress-text-new {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
        }

        .progress-bar-new {
            height: 6px;
            background: var(--nimbus-cloud);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill-new {
            height: 100%;
            background: var(--grenadine);
            border-radius: 3px;
        }

        .floating-course-new {
            position: absolute;
            background: var(--white);
            border-radius: 15px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .course-1-new {
            top: -20px;
            right: -20px;
        }

        .course-2-new {
            bottom: 40px;
            left: -30px;
        }

        .course-3-new {
            bottom: -20px;
            right: 20px;
        }

        .course-icon-new {
            width: 40px;
            height: 40px;
            background: var(--grenadine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
        }

        .course-info-new h4 {
            margin: 0;
            font-size: 1rem;
        }

        .course-info-new p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--ultimate-gray);
        }

        .dashboard-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background: var(--nimbus-cloud);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        .price-display-new {
            display: flex;
            align-items: baseline;
            justify-content: center;
        }

        .price-main-new {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--black-beauty);
        }

        .price-period-new {
            font-size: 1rem;
            color: var(--ultimate-gray);
            margin-left: 0.5rem;
        }

        .price-note-new {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
            text-align: center;
        }

        .course-stats-new {
            margin: 2rem 0;
        }

        .stat-number-new {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--grenadine);
        }

        .stat-label-new {
            font-size: 0.8rem;
            color: var(--ultimate-gray);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pricing-features li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .pricing-card {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--nimbus-cloud);
            transition: all 0.3s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .pricing-card.popular {
            border: 2px solid var(--grenadine);
        }

        .popular-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: var(--grenadine);
            color: var(--white);
            padding: 0.3rem 1rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .pricing-header h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--grenadine);
            margin-bottom: 0.5rem;
        }

        .price span {
            font-size: 1rem;
            color: var(--ultimate-gray);
        }

        .course-actions {
            margin-top: 2rem;
        }

        .btn-row {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--grenadine);
            color: var(--white);
        }

        .btn-primary:hover {
            background: #c5372d;
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--grenadine);
            border: 2px solid var(--grenadine);
        }

        .btn-outline:hover {
            background: var(--grenadine);
            color: var(--white);
        }

        .btn-plan {
            background: var(--black-beauty);
            color: var(--white);
        }

        .btn-plan:hover {
            background: #333;
            color: var(--white);
        }

        .btn-full {
            width: 100%;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .feature-icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(223, 63, 50, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--grenadine);
        }

        .feature-card h3 {
            margin-bottom: 1rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--ultimate-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .cta {
            padding: 6rem 0;
        }

        .cta-card {
            background: linear-gradient(135deg, var(--grenadine) 0%, #ff7a5a 100%);
            border-radius: 20px;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            color: var(--white);
        }

        @media (min-width: 768px) {
            .cta-card {
                flex-direction: row;
                align-items: center;
            }
        }

        .cta-content {
            flex: 1;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .cta-visual {
            flex: 0 0 200px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .cta-stat {
            text-align: center;
        }

        .cta-stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .cta-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .btn-outline-light {
            background: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .btn-outline-light:hover {
            background: var(--white);
            color: var(--grenadine);
        }

        .profile-nav .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 0.5rem 0;
            margin-top: 1rem;
        }

        .profile-nav .dropdown-item {
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            color: var(--black-beauty);
        }

        .profile-nav .dropdown-item:hover {
            background: rgba(223, 63, 50, 0.1);
            color: var(--grenadine);
        }

        .nav-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.5rem;
        }

        .nav-avatar-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
    </style>

    @yield('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">Knowly</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('courses') }}">Курсы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">О нас</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/free-lesson">Бесплатный урок</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item profile-nav dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Аватар" class="nav-avatar">
                            @else
                                <i class="fas fa-user nav-avatar-icon"></i>
                            @endif
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Дашборд</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Профиль</a></li>
                            <li><a class="dropdown-item" href="{{ route('enrollment.my-enrollments') }}"><i class="fas fa-book me-2"></i> Мои записи</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-primary-new" href="{{ route('signup') }}">Регистрация</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h3 class="mb-3">Knowly</h3>
                <p>Платформа для изучения английского языка с носителями. Индивидуальный подход и гарантия результата.</p>
            </div>
            <div class="col-lg-2 col-6 mb-4">
                <h5 class="mb-3">Курсы</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('courses') }}?filter=beginner">Для начинающих</a></li>
                    <li class="mb-2"><a href="{{ route('courses') }}?filter=business">Бизнес английский</a></li>
                    <li class="mb-2"><a href="{{ route('courses') }}?filter=conversational">Разговорный</a></li>
                    <li class="mb-2"><a href="{{ route('courses') }}?filter=exam">Подготовка к экзаменам</a></li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('universal-tests.index') }}">Тесты</a>
                        </li>
                    @endauth
                </ul>
            </div>
            <div class="col-lg-2 col-6 mb-4">
                <h5 class="mb-3">Компания</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/about">О нас</a></li>
                    <li class="mb-2"><a href="#">Преподаватели</a></li>
                    <li class="mb-2"><a href="#">Отзывы</a></li>
                    <li class="mb-2"><a href="#">Контакты</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="mb-3">Контакты</h5>
                <p><i class="fas fa-phone me-2"></i> +7 (999) 123-45-67</p>
                <p><i class="fas fa-envelope me-2"></i> hello@knowly.ru</p>
                <div class="mt-3">
                    <a href="#" class="me-3"><i class="fab fa-vk"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-telegram"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="mt-4 mb-4">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2025 Knowly. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="me-3">Политика конфиденциальности</a>
                <a href="#">Пользовательское соглашение</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
