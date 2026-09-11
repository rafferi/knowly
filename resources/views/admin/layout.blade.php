<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Админка Knowly</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #f5f7fa;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a2530 100%);
            color: white;
            position: fixed;
            height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            color: white;
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-header h2 i {
            color: #4CAF50;
        }

        .nav-menu {
            padding: 20px 0;
        }

        .nav-section {
            padding: 10px 0;
        }

        .nav-section-title {
            padding: 0 20px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            text-decoration: none;
            margin: 2px 0;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #4CAF50;
        }

        .nav-link.active {
            background: rgba(76, 175, 80, 0.2);
            color: white;
            border-left-color: #4CAF50;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
        }

        .user-role {
            font-size: 12px;
            color: rgba(255,255,255,0.6);
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .btn-outline {
            background: white;
            border: 1px solid #ddd;
            color: #333;
        }
        .btn-outline:hover {
            background: #f8f9fa;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ddd;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a:hover {
            background: #f8f9fa;
        }
        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Тёмная тема для таблиц */
        .table-dark {
            background: #2c3e50;
            color: white;
        }
        .table-dark th {
            background: #34495e;
            color: white;
        }
        .table-dark td {
            border-color: #34495e;
        }

        /* Анимации */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Мобильная адаптация */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1001;
                background: white;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                cursor: pointer;
            }
        }
    </style>
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-graduation-cap"></i>
                Knowly Admin
            </h2>
        </div>

        <div class="nav-menu">
            <div class="nav-section">
                <div class="nav-section-title">Главная</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Дашборд</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Управление</div>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Пользователи</span>
                </a>
                <a href="{{ route('admin.courses') }}" class="nav-link {{ request()->is('admin/courses*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Курсы</span>
                </a>
                <a href="{{ route('admin.enrollments') }}" class="nav-link {{ request()->is('admin/enrollments*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i>
                    <span>Записи</span>
                </a>
                <a href="{{ route('admin.lessons') }}" class="nav-link {{ request()->is('admin/lessons*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Уроки</span>
                </a>
                <a href="{{ route('admin.tests') }}" class="nav-link {{ request()->is('admin/tests*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Тесты</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Отчеты</div>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->is('admin/reports') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Все отчеты</span>
                </a>
                <a href="{{ route('admin.reports.users') }}" class="nav-link {{ request()->is('admin/reports/users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Пользователи</span>
                </a>
                <a href="{{ route('admin.reports.revenue') }}" class="nav-link {{ request()->is('admin/reports/revenue') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Финансы</span>
                </a>
                <a href="{{ route('admin.reports.learning-progress') }}" class="nav-link {{ request()->is('admin/reports/learning-progress') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Прогресс</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Экспорт</div>
                <a href="{{ route('admin.export.users') }}" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    <span>Пользователи</span>
                </a>
                <a href="{{ route('admin.export.courses') }}" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    <span>Курсы</span>
                </a>
                <a href="{{ route('admin.export.enrollments') }}" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    <span>Записи</span>
                </a>
                <a href="{{ route('admin.export.revenue') }}" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    <span>Выручка</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Система</div>
                <a href="#" class="nav-link" onclick="alert('Настройки в разработке')">
                    <i class="fas fa-cog"></i>
                    <span>Настройки</span>
                </a>
                <a href="#" class="nav-link" onclick="alert('Логи в разработке')">
                    <i class="fas fa-history"></i>
                    <span>Логи системы</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Администратор</div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="/" class="btn btn-outline" style=" border-color: rgba(255,255,255,0.2);">
                    <i class="fas fa-external-link-alt"></i>
                    На сайт
                </a>
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%;  border-color: rgba(255,255,255,0.2);">
                        <i class="fas fa-sign-out-alt"></i>
                        Выйти
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>@yield('page-title', 'Админ-панель')</h1>
            <div class="header-actions">
                <button class="btn btn-outline" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                    Тема
                </button>
                <div class="current-time" style="padding: 8px 15px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
                    <i class="far fa-clock"></i>
                    <span id="current-time"></span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success fade-in">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger fade-in">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('styles')
@stack('scripts')

<script>
    // Обновление времени в реальном времени
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('ru-RU');
        document.getElementById('current-time').textContent = timeString;
    }

    setInterval(updateTime, 1000);
    updateTime();

    // Переключение темы
    function toggleTheme() {
        const body = document.body;
        const isDark = body.classList.contains('dark-theme');

        if (isDark) {
            body.classList.remove('dark-theme');
            localStorage.setItem('admin-theme', 'light');
        } else {
            body.classList.add('dark-theme');
            localStorage.setItem('admin-theme', 'dark');
        }
    }

    // Загрузка темы из localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('admin-theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
        }

        // Добавляем стили для темной темы
        const style = document.createElement('style');
        style.textContent = `
            .dark-theme {
                background: #1a1a1a;
                color: #f0f0f0;
            }
            .dark-theme .main-content {
                background: #1a1a1a;
            }
            .dark-theme .card {
                background: #2d2d2d;
                color: #f0f0f0;
            }
            .dark-theme .header {
                background: #2d2d2d;
                color: #f0f0f0;
            }
            .dark-theme table {
                color: #f0f0f0;
            }
            .dark-theme th {
                background: #3d3d3d;
                color: #f0f0f0;
            }
            .dark-theme td {
                border-color: #3d3d3d;
            }
        `;
        document.head.appendChild(style);
    });

    // Подтверждение удаления с кастомным диалогом
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');

        deleteForms.forEach(form => {
            const button = form.querySelector('button[type="submit"]');

            if (button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (confirm('Вы уверены, что хотите удалить этот элемент? Это действие нельзя отменить.')) {
                        form.submit();
                    }
                });
            }
        });
    });

    // Анимация загрузки
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.querySelector('.main-content');
        content.classList.add('fade-in');
    });
</script>
</body>
</html>
