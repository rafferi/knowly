@extends('admin.layout')

@section('title', 'Отчеты')
@section('page-title', 'Отчеты')

@section('content')
    <div class="card">
        <h2>Доступные отчеты</h2>

        <div class="reports-grid">
            <a href="{{ route('admin.reports.users') }}" class="report-card">
                <div class="report-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Отчет по пользователям</h3>
                <p>Статистика по пользователям, фильтрация по ролям и уровням</p>
            </a>

            <a href="{{ route('admin.reports.enrollments') }}" class="report-card">
                <div class="report-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3>Отчет по записям</h3>
                <p>Детальная информация о записях на курсы</p>
            </a>

            <a href="{{ route('admin.reports.revenue') }}" class="report-card">
                <div class="report-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>Финансовый отчет</h3>
                <p>Выручка по месяцам и общая статистика</p>
            </a>

            <a href="{{ route('admin.reports.learning-progress') }}" class="report-card">
                <div class="report-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Прогресс обучения</h3>
                <p>Статистика по завершенным урокам и тестам</p>
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .report-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            border: 1px solid #eaeaea;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-color: #667eea;
        }

        .report-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 24px;
        }

        .report-card h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #2c3e50;
        }

        .report-card p {
            margin: 0;
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
    </style>
@endpush
