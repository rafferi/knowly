@extends('app')

@section('title', 'Мои домашние задания')

@section('content')
    <div class="dashboard-hero" style="min-height: 100vh; padding-top: 120px;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Мои домашние задания</h1>
                <div class="homework-stats">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['total'] }}</div>
                            <div class="stat-label">Всего</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['submitted'] }}</div>
                            <div class="stat-label">Отправлено</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['graded'] }}</div>
                            <div class="stat-label">Проверено</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ number_format($stats['average_score'], 1) }}</div>
                            <div class="stat-label">Средний балл</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Фильтры -->
            <div class="filters mb-4">
                <div class="btn-group" role="group">
                    <a href="{{ route('homeworks.index', ['filter' => 'all']) }}"
                       class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Все
                    </a>
                    <a href="{{ route('homeworks.index', ['filter' => 'submitted']) }}"
                       class="btn {{ $filter === 'submitted' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Отправленные
                    </a>
                    <a href="{{ route('homeworks.index', ['filter' => 'graded']) }}"
                       class="btn {{ $filter === 'graded' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Проверенные
                    </a>
                    <a href="{{ route('homeworks.index', ['filter' => 'returned_for_revision']) }}"
                       class="btn {{ $filter === 'returned_for_revision' ? 'btn-primary' : 'btn-outline-primary' }}">
                        На доработке
                    </a>
                </div>
            </div>

            <!-- Список домашних заданий -->
            @if($homeworks->count() > 0)
                <div class="homeworks-list">
                    @foreach($homeworks as $homework)
                        <div class="dashboard-card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-start">
                                            <div class="homework-status me-3">
                                                @switch($homework->status)
                                                    @case('submitted')
                                                        <span class="badge bg-warning">Отправлено</span>
                                                        @break
                                                    @case('graded')
                                                        <span class="badge bg-success">Проверено</span>
                                                        @break
                                                    @case('returned_for_revision')
                                                        <span class="badge bg-danger">На доработке</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">В работе</span>
                                                @endswitch
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $homework->lesson->title ?? 'Урок удален' }}</h5>
                                                <div class="homework-meta">
                                                <span class="text-muted me-3">
                                                    <i class="fas fa-book"></i> {{ $homework->course->title ?? '' }}
                                                </span>
                                                    <span class="text-muted me-3">
                                                    <i class="fas fa-clock"></i> {{ $homework->submitted_at ? $homework->submitted_at->format('d.m.Y H:i') : '' }}
                                                </span>
                                                    @if($homework->score)
                                                        <span class="text-success">
                                                        <i class="fas fa-star"></i> {{ $homework->score }}/{{ $homework->max_score }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('homeworks.show', $homework) }}"
                                               class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Подробнее
                                            </a>
                                            @if($homework->status === 'returned_for_revision')
                                                <a href="{{ route('homeworks.edit', $homework) }}"
                                                   class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Исправить
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Пагинация -->
                <div class="d-flex justify-content-center">
                    {{ $homeworks->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> У вас пока нет домашних заданий.
                </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .homework-stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .stat-card {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: var(--grenadine);
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection
