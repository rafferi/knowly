@extends('admin.layout')

@section('title', 'Финансовый отчет')
@section('page-title', 'Финансовый отчет')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Финансовый отчет</h2>
            <div>
                <a href="{{ route('admin.export.revenue') }}" class="btn btn-primary">
                    <i class="fas fa-file-export"></i> Экспорт CSV
                </a>
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Печать
                </button>
            </div>
        </div>

        <!-- Фильтры -->
        <form method="GET" action="{{ route('admin.reports.revenue') }}" class="filters-form">
            <div class="filter-row">
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
                    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-outline">Сбросить</a>
                </div>
            </div>
        </form>

        <!-- Статистика -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-value">{{ number_format($stats['total_revenue'], 0, '', ' ') }} ₽</div>
                <div class="stat-label">Общая выручка</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($stats['average_revenue'], 0, '', ' ') }} ₽</div>
                <div class="stat-label">Средний чек</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['count'] }}</div>
                <div class="stat-label">Количество оплат</div>
            </div>
        </div>

        <!-- График по месяцам (простой) -->
        <div class="card">
            <h3>Выручка по месяцам ({{ date('Y') }})</h3>
            <div style="margin-top: 20px;">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Месяц</th>
                        <th>Выручка</th>
                        <th>График</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($monthlyRevenue as $month)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $month['month'])->format('F') }}</td>
                            <td>{{ number_format($month['revenue'], 0, '', ' ') }} ₽</td>
                            <td>
                                <div style="width: 200px; height: 20px; background: #e9ecef; border-radius: 10px; overflow: hidden;">
                                    @php
                                        $maxRevenue = max(array_column($monthlyRevenue, 'revenue'));
                                        $width = $maxRevenue > 0 ? ($month['revenue'] / $maxRevenue) * 100 : 0;
                                    @endphp
                                    <div style="width: {{ $width }}%; height: 100%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Таблица последних оплат -->
        <div class="card">
            <h3>Последние оплаты</h3>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Курс</th>
                        <th>Сумма</th>
                        <th>Дата</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->id }}</td>
                            <td>{{ $enrollment->user->name }}</td>
                            <td>{{ $enrollment->course->title }}</td>
                            <td>{{ number_format($enrollment->price, 0, '', ' ') }} ₽</td>
                            <td>{{ $enrollment->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Итоговая информация -->
        <div class="report-summary">
            <h3>Итоги отчета</h3>
            <p>Отчет сгенерирован: {{ now()->format('d.m.Y H:i') }}</p>
            <p>Общая выручка: {{ number_format($stats['total_revenue'], 0, '', ' ') }} ₽</p>
            <p>Средний чек: {{ number_format($stats['average_revenue'], 0, '', ' ') }} ₽</p>
            <p>Количество оплат: {{ $stats['count'] }}</p>
            @if(request()->hasAny(['date_from', 'date_to']))
                <p>Примененные фильтры:</p>
                <ul>
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
    </style>
@endpush
