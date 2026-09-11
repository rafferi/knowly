@extends('admin.layout')

@section('title', 'Отчет по записям')
@section('page-title', 'Отчет по записям')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Отчет по записям на курсы</h2>
            <div>
                <a href="{{ route('admin.export.enrollments') }}" class="btn btn-primary">
                    <i class="fas fa-file-export"></i> Экспорт CSV
                </a>
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Печать
                </button>
            </div>
        </div>

        <!-- Фильтры -->
        <form method="GET" action="{{ route('admin.reports.enrollments') }}" class="filters-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Статус</label>
                    <select name="status" class="filter-select">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидание</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активна</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершена</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Статус оплаты</label>
                    <select name="payment_status" class="filter-select">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Ожидание</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Оплачено</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Ошибка</option>
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
                    <a href="{{ route('admin.reports.enrollments') }}" class="btn btn-outline">Сбросить</a>
                </div>
            </div>
        </form>

        <!-- Статистика -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Всего записей</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Активных</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Завершенных</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Ожидающих</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($stats['total_revenue'], 0, '', ' ') }} ₽</div>
                <div class="stat-label">Общая выручка</div>
            </div>
        </div>

        <!-- Таблица -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Email</th>
                    <th>Курс</th>
                    <th>Статус</th>
                    <th>Оплата</th>
                    <th>Цена</th>
                    <th>Дата записи</th>
                </tr>
                </thead>
                <tbody>
                @foreach($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->id }}</td>
                        <td>{{ $enrollment->user->name }}</td>
                        <td>{{ $enrollment->user->email }}</td>
                        <td>{{ $enrollment->course->title }}</td>
                        <td>
                            @if($enrollment->status == 'active')
                                <span class="badge bg-success">Активна</span>
                            @elseif($enrollment->status == 'pending')
                                <span class="badge bg-warning">Ожидание</span>
                            @elseif($enrollment->status == 'completed')
                                <span class="badge bg-info">Завершена</span>
                            @elseif($enrollment->status == 'cancelled')
                                <span class="badge bg-danger">Отменена</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->payment_status == 'paid')
                                <span class="badge bg-success">Оплачено</span>
                            @elseif($enrollment->payment_status == 'pending')
                                <span class="badge bg-warning">Ожидание</span>
                            @elseif($enrollment->payment_status == 'failed')
                                <span class="badge bg-danger">Ошибка</span>
                            @endif
                        </td>
                        <td>{{ $enrollment->price ? number_format($enrollment->price, 0, '', ' ') . ' ₽' : 'Бесплатно' }}</td>
                        <td>{{ $enrollment->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Итоговая информация -->
        <div class="report-summary">
            <h3>Итоги отчета</h3>
            <p>Отчет сгенерирован: {{ now()->format('d.m.Y H:i') }}</p>
            <p>Общее количество записей: {{ $enrollments->count() }}</p>
            <p>Общая выручка: {{ number_format($stats['total_revenue'], 0, '', ' ') }} ₽</p>
            @if(request()->hasAny(['status', 'payment_status', 'date_from', 'date_to']))
                <p>Примененные фильтры:</p>
                <ul>
                    @if(request('status'))
                        <li>Статус: {{ request('status') }}</li>
                    @endif
                    @if(request('payment_status'))
                        <li>Статус оплаты: {{ request('payment_status') }}</li>
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
