@extends('app')
@section('title', 'Программа курса - ' . $course->title)
@section('content')
    <div class="roadmap-container">
        <div class="roadmap-header">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active">Программа обучения</li>
                </ol>
            </nav>
            <div class="header-content">
                <h1>Программа курса: {{ $course->title }}</h1>
                <p class="subtitle">Последовательное прохождение уроков и тестов</p>
                @auth
                    <div class="header-actions">
                        <a href="{{ route('lectures.next', ['courseSlug' => $course->slug]) }}" class="btn btn-primary">
                            <i class="fas fa-play-circle"></i> Продолжить обучение
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="roadmap-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $roadmapItems->where('type', 'lesson')->count() }}</div>
                <div class="stat-label">Уроков</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $roadmapItems->where('type', 'test')->count() }}</div>
                <div class="stat-label">Тестов</div>
            </div>
            <div class="stat-card">
                @php $completed = $roadmapItems->where('is_completed', true)->count(); $total = $roadmapItems->count(); @endphp
                <div class="stat-number">{{ $completed }}/{{ $total }}</div>
                <div class="stat-label">Пройдено</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $total > 0 ? round(($completed / $total) * 100) : 0 }}%</div>
                <div class="stat-label">Прогресс</div>
            </div>
        </div>

        <div class="roadmap-timeline">
            @foreach($roadmapItems as $index => $item)
                @php
                    $isCompleted = $item['is_completed'] ?? false;
                    $isAvailable = $item['is_available'] ?? false;
                @endphp
                <div class="timeline-item {{ $isCompleted ? 'completed' : '' }} {{ !$isAvailable ? 'locked' : '' }}">
                    <div class="timeline-connector">
                        @if($index > 0)
                            <div class="connector-line {{ $roadmapItems[$index-1]['is_completed'] ? 'completed' : '' }}"></div>
                        @endif
                        <div class="timeline-dot {{ $isCompleted ? 'completed' : ($isAvailable ? 'available' : 'locked') }}">
                            @if($isCompleted) <i class="fas fa-check"></i>
                            @elseif(!$isAvailable) <i class="fas fa-lock"></i>
                            @else <span>{{ $index + 1 }}</span>
                            @endif
                        </div>
                        @if($index < $roadmapItems->count() - 1)
                            <div class="connector-line {{ $isCompleted ? 'completed' : '' }}"></div>
                        @endif
                    </div>
                    <div class="timeline-content">
                        <div class="item-card {{ $item['type'] }} {{ $isCompleted ? 'completed' : '' }} {{ !$isAvailable ? 'locked' : '' }}">
                            <div class="item-header">
                                <div class="item-type {{ $item['type'] }}">
                                    @if($item['type'] === 'lesson') <i class="fas fa-book"></i> Урок
                                    @else <i class="fas fa-clipboard-check"></i> Тест @endif
                                </div>
                                <div class="item-status">
                                    @if($isCompleted) <span class="badge bg-success"><i class="fas fa-check"></i> Пройдено</span>
                                    @elseif($isAvailable) <span class="badge bg-warning"><i class="fas fa-play"></i> Доступно</span>
                                    @else <span class="badge bg-secondary"><i class="fas fa-lock"></i> Заблокировано</span> @endif
                                </div>
                            </div>
                            <div class="item-body">
                                <h3>{{ $item['title'] }}</h3>
                                <p class="item-description">{{ $item['description'] ?? 'Без описания' }}</p>
                                @if($isCompleted && isset($item['user_progress']))
                                    <div class="item-progress">
                                        @if($item['type'] === 'lesson')
                                            <span><i class="fas fa-calendar"></i> Завершено: {{ $item['user_progress']->completed_at->format('d.m.Y') }}</span>
                                        @else
                                            <span><i class="fas fa-star"></i> {{ $item['user_progress']->score }}/{{ $item['item']->total_points }} баллов</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="item-actions">
                                @if($isAvailable)
                                    @if($item['type'] === 'lesson')
                                        <a href="{{ route('lectures.show', ['courseSlug' => $course->slug, 'lessonId' => $item['id']]) }}" class="btn {{ $isCompleted ? 'btn-outline' : 'btn-primary' }}">
                                            {{ $isCompleted ? 'Повторить' : 'Начать урок' }}
                                        </a>
                                    @else
                                        <a href="{{ route('universal-tests.show', $item['id']) }}" class="btn {{ $isCompleted ? 'btn-outline' : 'btn-primary' }}">
                                            {{ $isCompleted ? 'Пройти снова' : 'Начать тест' }}
                                        </a>
                                    @endif
                                @else
                                    <button class="btn btn-secondary" disabled><i class="fas fa-lock"></i> Недоступно</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <style>
        .roadmap-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .roadmap-header { margin-bottom: 3rem; text-align: center; }
        .header-content h1 { font-size: 2.5rem; color: var(--black-beauty); }
        .subtitle { color: var(--ultimate-gray); font-size: 1.1rem; margin-bottom: 1.5rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-primary { background: var(--grenadine); color: white; } .btn-primary:hover { background: #df3f32; transform: translateY(-2px); }
        .btn-outline { background: white; border: 2px solid var(--grenadine); color: var(--grenadine); } .btn-outline:hover { background: var(--grenadine); color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .roadmap-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card { background: white; border-radius: 15px; padding: 1.5rem; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 2px solid var(--nimbus-cloud); }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: var(--grenadine); }
        .stat-label { color: var(--ultimate-gray); font-size: 1rem; font-weight: 600; }
        .roadmap-timeline { position: relative; padding: 2rem 0; }
        .timeline-item { display: flex; margin-bottom: 2rem; }
        .timeline-connector { display: flex; flex-direction: column; align-items: center; width: 60px; flex-shrink: 0; }
        .connector-line { width: 2px; flex: 1; background: var(--nimbus-cloud); }
        .connector-line.completed { background: var(--grenadine); }
        .timeline-dot { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; border: 3px solid; background: white; }
        .timeline-dot.available { border-color: var(--grenadine); color: var(--grenadine); }
        .timeline-dot.completed { border-color: #28a745; background: #28a745; color: white; }
        .timeline-dot.locked { border-color: #6c757d; color: #6c757d; }
        .timeline-content { flex: 1; padding-left: 1.5rem; }
        .item-card { background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 2px solid; }
        .item-card.lesson { border-color: #2196f3; } .item-card.test { border-color: #ffc107; }
        .item-card.completed { border-color: #28a745; } .item-card.locked { border-color: #6c757d; opacity: 0.7; }
        .item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .item-type { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.3rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem; }
        .item-type.lesson { background: rgba(33,150,243,0.1); color: #2196f3; } .item-type.test { background: rgba(255,193,7,0.1); color: #856404; }
        .badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .bg-success { background: #28a745; color: white; } .bg-warning { background: #ffc107; color: black; } .bg-secondary { background: #6c757d; color: white; }
        .item-body h3 { margin-bottom: 0.5rem; color: var(--black-beauty); font-size: 1.3rem; }
        .item-description { color: var(--ultimate-gray); margin-bottom: 1rem; line-height: 1.6; }
        .item-progress { display: flex; gap: 1.5rem; color: var(--ultimate-gray); font-size: 0.9rem; margin-bottom: 1rem; }
        .item-actions { text-align: right; }
    </style>
@endsection
