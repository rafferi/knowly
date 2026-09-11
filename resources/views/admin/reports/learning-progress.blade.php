@extends('admin.layout')

@section('title', 'Отчет по прогрессу обучения')
@section('page-title', 'Отчет по прогрессу обучения')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Отчет по прогрессу обучения</h2>
            <div>
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Печать
                </button>
            </div>
        </div>

        <!-- Статистика по курсам -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-value">{{ $courses->count() }}</div>
                <div class="stat-label">Всего курсов</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $courses->sum('enrollments_count') }}</div>
                <div class="stat-label">Всего записей</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $usersProgress->sum('total_lessons_completed') }}</div>
                <div class="stat-label">Пройдено уроков</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($courses->avg('avg_progress') ?? 0, 1) }}%</div>
                <div class="stat-label">Средний прогресс</div>
            </div>
        </div>

        <!-- Популярные курсы -->
        <div class="card">
            <h3>Популярные курсы (по количеству записей)</h3>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Курс</th>
                        <th>Записей</th>
                        <th>Уроков</th>
                        <th>Средний прогресс</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($popularCourses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->enrollments_count }}</td>
                            <td>{{ $course->lessons_count }}</td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $course->avg_progress }}%"></div>
                                    <span class="progress-text">{{ round($course->avg_progress, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Прогресс пользователей -->
        <div class="card">
            <h3>Активные пользователи</h3>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Записей на курсы</th>
                        <th>Пройдено уроков</th>
                        <th>Уровень</th>
                        <th>XP</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($usersProgress as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @if($user->avatar)
                                        <img src="{{ asset($user->avatar) }}" alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%;">
                                    @else
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->enrollments_count ?? 0 }}</td>
                            <td>{{ $user->total_lessons_completed }}</td>
                            <td>{{ $user->level }}</td>
                            <td>{{ $user->xp }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Общая статистика по курсам -->
        <div class="card">
            <h3>Все курсы</h3>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Курс</th>
                        <th>Записей</th>
                        <th>Уроков</th>
                        <th>Средний прогресс</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->enrollments_count }}</td>
                            <td>{{ $course->lessons_count }}</td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $course->avg_progress }}%"></div>
                                    <span class="progress-text">{{ round($course->avg_progress, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: white;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #555;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .data-table tr:hover {
            background: #f9f9f9;
        }

        .progress-bar {
            position: relative;
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        .progress-text {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            line-height: 20px;
            color: #333;
            font-weight: bold;
        }
    </style>
@endpush
