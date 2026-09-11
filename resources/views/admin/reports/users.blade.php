@extends('admin.layout')

@section('title', 'Отчет по пользователям')
@section('page-title', 'Отчет по пользователям')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Отчет по пользователям</h2>
            <div>
                <a href="{{ route('admin.export.users') }}" class="btn btn-primary">
                    <i class="fas fa-file-export"></i> Экспорт CSV
                </a>
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Печать
                </button>
            </div>
        </div>

        <!-- Фильтры -->
        <form method="GET" action="{{ route('admin.reports.users') }}" class="filters-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Роль</label>
                    <select name="role" class="filter-select">
                        <option value="">Все роли</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Студент</option>
                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Преподаватель</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Администратор</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Уровень</label>
                    <select name="level" class="filter-select">
                        <option value="">Все уровни</option>
                        <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Начинающий</option>
                        <option value="elementary" {{ request('level') == 'elementary' ? 'selected' : '' }}>Элементарный</option>
                        <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Средний</option>
                        <option value="upper_intermediate" {{ request('level') == 'upper_intermediate' ? 'selected' : '' }}>Выше среднего</option>
                        <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Продвинутый</option>
                        <option value="master" {{ request('level') == 'master' ? 'selected' : '' }}>Мастер</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Дата с</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Дата по</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input">
                </div>

                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                    <a href="{{ route('admin.reports.users') }}" class="btn btn-outline">Сбросить</a>
                </div>
            </div>
        </form>

        <!-- Статистика -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Всего пользователей</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['students'] }}</div>
                <div class="stat-label">Студентов</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['teachers'] }}</div>
                <div class="stat-label">Преподавателей</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['admins'] }}</div>
                <div class="stat-label">Администраторов</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['avg_xp'] }}</div>
                <div class="stat-label">Средний XP</div>
            </div>
        </div>

        <!-- Таблица -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Уровень</th>
                    <th>XP</th>
                    <th>Телефон</th>
                    <th>Дата регистрации</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
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
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Админ</span>
                            @elseif($user->role == 'teacher')
                                <span class="badge bg-info">Преподаватель</span>
                            @else
                                <span class="badge bg-success">Студент</span>
                            @endif
                        </td>
                        <td>{{ $user->level }}</td>
                        <td>{{ $user->xp }}</td>
                        <td>{{ $user->phone ?? 'Не указан' }}</td>
                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Итоговая информация -->
        <div class="report-summary">
            <h3>Итоги отчета</h3>
            <p>Отчет сгенерирован: {{ now()->format('d.m.Y H:i') }}</p>
            <p>Общее количество записей: {{ $users->count() }}</p>
            @if(request()->hasAny(['role', 'level', 'date_from', 'date_to']))
                <p>Примененные фильтры:</p>
                <ul>
                    @if(request('role'))
                        <li>Роль: {{ request('role') }}</li>
                    @endif
                    @if(request('level'))
                        <li>Уровень: {{ request('level') }}</li>
                    @endif
                    @if(request('date_from'))
                        <li>Дата с: {{ request('date_from') }}</li>
                    @endif
                    @if(request('date_to'))
                        <li>Дата по: {{ request('date_to') }}</li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .filters-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .filter-select, .filter-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

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

        .report-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }

        .report-summary h3 {
            margin-top: 0;
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 10px;
        }

        .bg-success { background: #28a745; color: white; }
        .bg-danger { background: #dc3545; color: white; }
        .bg-info { background: #17a2b8; color: white; }
    </style>
@endpush
