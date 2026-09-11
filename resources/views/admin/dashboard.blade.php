@extends('admin.layout')

@section('title', 'Дашборд')
@section('page-title', 'Дашборд')

@section('content')
    <!-- Статистика в виде карточек -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e3f2fd;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Всего пользователей</h3>
                <p class="stat-number">{{ $stats['total_users'] }}</p>
                <p class="stat-change">
                    <i class="fas fa-arrow-up text-success"></i>
                    +{{ $stats['new_users_30d'] }} за 30 дней
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #e8f5e9;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <h3>Курсы</h3>
                <p class="stat-number">{{ $stats['total_courses'] }}</p>
                <p class="stat-change">{{ $stats['total_lessons'] }} уроков</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fff3e0;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
                <h3>Записи</h3>
                <p class="stat-number">{{ $stats['active_enrollments'] }}</p>
                <p class="stat-change">
                    <span class="badge bg-warning">{{ $stats['pending_enrollments'] }} ожидают</span>
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fce4ec;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
                <h3>Выручка</h3>
                <p class="stat-number">{{ number_format($stats['revenue'], 0, '', ' ') }} ₽</p>
                <p class="stat-change text-success">
                    +{{ number_format($stats['revenue_30d'], 0, '', ' ') }} ₽ за 30 дней
                </p>
            </div>
        </div>
    </div>

    <!-- Два столбца с таблицами -->
    <div class="dashboard-columns">
        <!-- Левый столбец -->
        <div class="dashboard-column">
            <!-- Последние пользователи -->
            <div class="card">
                <div class="card-header">
                    <h3>Последние пользователи</h3>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline">Все пользователи</a>
                </div>
                <div class="card-body">
                    <table class="simple-table">
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'teacher' ? 'bg-info' : 'bg-success') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Популярные курсы -->
            <div class="card">
                <div class="card-header">
                    <h3>Популярные курсы</h3>
                    <a href="{{ route('admin.courses') }}" class="btn btn-sm btn-outline">Все курсы</a>
                </div>
                <div class="card-body">
                    <table class="simple-table">
                        <thead>
                        <tr>
                            <th>Курс</th>
                            <th>Записей</th>
                            <th>Цена</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($popularCourses as $course)
                            <tr>
                                <td>{{ Str::limit($course->title, 30) }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $course->enrollments_count }}</span>
                                </td>
                                <td>{{ number_format($course->price, 0, '', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Правый столбец -->
        <div class="dashboard-column">
            <!-- Последние записи -->
            <div class="card">
                <div class="card-header">
                    <h3>Последние записи</h3>
                    <a href="{{ route('admin.enrollments') }}" class="btn btn-sm btn-outline">Все записи</a>
                </div>
                <div class="card-body">
                    <table class="simple-table">
                        <thead>
                        <tr>
                            <th>Пользователь</th>
                            <th>Курс</th>
                            <th>Статус</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentEnrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->user->name }}</td>
                                <td>{{ Str::limit($enrollment->course->title, 20) }}</td>
                                <td>
                                    @if($enrollment->status == 'active')
                                        <span class="badge bg-success">Активна</span>
                                    @elseif($enrollment->status == 'pending')
                                        <span class="badge bg-warning">Ожидание</span>
                                    @elseif($enrollment->status == 'completed')
                                        <span class="badge bg-info">Завершена</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->created_at->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="card">
                <div class="card-header">
                    <h3>Быстрые действия</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.users.create') }}" class="quick-action">
                            <i class="fas fa-user-plus"></i>
                            <span>Добавить пользователя</span>
                        </a>
                        <a href="{{ route('admin.courses.create') }}" class="quick-action">
                            <i class="fas fa-plus-circle"></i>
                            <span>Добавить курс</span>
                        </a>
                        <a href="{{ route('admin.enrollments.create') }}" class="quick-action">
                            <i class="fas fa-user-graduate"></i>
                            <span>Добавить запись</span>
                        </a>
                        <a href="{{ route('admin.lessons.create') }}" class="quick-action">
                            <i class="fas fa-book"></i>
                            <span>Добавить урок</span>
                        </a>
                        <a href="{{ route('admin.tests.create') }}" class="quick-action">
                            <i class="fas fa-file-alt"></i>
                            <span>Добавить тест</span>
                        </a>
                        <a href="#" class="quick-action" onclick="alert('Функция в разработке')">
                            <i class="fas fa-chart-bar"></i>
                            <span>Отчеты</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Дополнительная статистика -->
    <div class="card mt-3">
        <div class="card-header">
            <h3>Общая статистика</h3>
        </div>
        <div class="card-body">
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-label">Модулей в системе</div>
                    <div class="stat-value">{{ $stats['total_modules'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Тестов в системе</div>
                    <div class="stat-value">{{ $stats['total_tests'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Средний балл тестов</div>
                    <div class="stat-value">{{ round($stats['avg_test_score'], 1) }}%</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Новых записей (30 дней)</div>
                    <div class="stat-value">{{ $stats['new_enrollments_30d'] }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Общие стили для дашборда */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
            color: #333;
        }

        .stat-info h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .stat-change {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        /* Два столбца */
        .dashboard-columns {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Карточки */
        .card {
            background: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .card-body {
            padding: 20px;
        }

        /* Простые таблицы */
        .simple-table {
            width: 100%;
            border-collapse: collapse;
        }

        .simple-table th {
            text-align: left;
            padding: 10px;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid #eee;
        }

        .simple-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .simple-table tr:hover {
            background: #f9f9f9;
        }

        /* Бейджи */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 10px;
        }

        .bg-primary { background: #007bff; color: white; }
        .bg-success { background: #28a745; color: white; }
        .bg-danger { background: #dc3545; color: white; }
        .bg-warning { background: #ffc107; color: black; }
        .bg-info { background: #17a2b8; color: white; }

        /* Быстрые действия */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            border: 1px solid #dee2e6;
        }

        .quick-action:hover {
            background: #007bff;
            color: white;
            transform: translateY(-3px);
            border-color: #007bff;
        }

        .quick-action i {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .quick-action span {
            font-size: 14px;
            text-align: center;
        }

        /* Дополнительная статистика */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* Кнопки */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-outline {
            background: white;
            border: 1px solid #dee2e6;
            color: #333;
        }

        .btn-outline:hover {
            background: #f8f9fa;
        }

        .mt-3 {
            margin-top: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard loaded');
            // Здесь можно добавить JavaScript для интерактивности
        });
    </script>
@endpush
