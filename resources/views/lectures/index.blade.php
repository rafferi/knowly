@extends('app')

@section('title', 'Программа обучения - ' . $course->title)

@section('content')
    <div class="lesson-container">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active">Программа обучения</li>
                </ol>
            </nav>

            <div class="row mb-4 align-items-center">
                <div class="col-md-8">
                    <h1 class="section-title">{{ $course->title }}</h1>
                    <p class="text-muted">{{ $course->short_description }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    @php
                        $nextItem = $roadmapItems->firstWhere('is_available', true);
                    @endphp
                    @if($nextItem)
                        <a href="{{ $nextItem['url'] }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-play-circle"></i> Продолжить обучение
                        </a>
                    @else
                        <button class="btn btn-success btn-lg" disabled>
                            <i class="fas fa-check-circle"></i> Все пройдено!
                        </button>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="roadmap-list">
                @foreach($roadmapItems as $item)
                    <div class="lesson-item {{ $item['is_completed'] ? 'completed' : ($item['is_available'] ? 'available' : 'locked') }}">
                        <div class="lesson-time">
                            @if($item['type'] === 'lesson')
                                @if($item['is_completed'])
                                    <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                                @elseif($item['is_available'])
                                    <span class="badge bg-primary rounded-circle p-2">{{ $loop->iteration }}</span>
                                @else
                                    <i class="fas fa-lock text-secondary" style="font-size: 1.5rem;"></i>
                                @endif
                            @else {{-- test --}}
                            @if($item['is_completed'])
                                <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                                <span class="badge bg-success ms-1">Пройден</span>
                            @elseif($item['is_available'])
                                <i class="fas fa-clipboard-list text-primary" style="font-size: 1.5rem;"></i>
                            @else
                                <i class="fas fa-lock text-secondary" style="font-size: 1.5rem;"></i>
                            @endif
                            @endif
                        </div>

                        <div class="lesson-info flex-grow-1">
                            <h4 class="mb-1">
                                @if($item['type'] === 'lesson')
                                    <i class="fas fa-book-open me-2 text-primary"></i>
                                @else
                                    <i class="fas fa-clipboard-check me-2 text-warning"></i>
                                @endif
                                {{ $item['title'] }}
                                @if($item['type'] === 'test' && $item['is_completed'])
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-check"></i> Пройден
                                    </span>
                                @endif
                            </h4>
                            <p class="text-muted small mb-0">
                                @if($item['is_completed'])
                                    <i class="far fa-calendar-alt"></i> Завершено
                                @elseif($item['is_available'])
                                    <i class="fas fa-arrow-right text-primary"></i> Доступно
                                @else
                                    <i class="fas fa-lock text-secondary"></i> Заблокировано
                                @endif
                            </p>
                        </div>

                        <div class="lesson-actions d-flex align-items-center gap-2">
                            @if($item['is_completed'])
                                <a href="{{ $item['url'] }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-redo"></i> Повторить
                                </a>
                            @elseif($item['is_available'])
                                <a href="{{ $item['url'] }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-play"></i> {{ $item['type'] === 'lesson' ? 'Начать' : 'Пройти тест' }}
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-lock"></i> Заблокировано
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
