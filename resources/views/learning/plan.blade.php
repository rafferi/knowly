@extends('app')

@section('title', 'Учебный план - ' . $course->title)

@section('content')
    <div class="dashboard-hero" style="min-height: 100vh; padding-top: 120px;">
        <div class="container">
            <!-- Хедер курса -->
            <div class="dashboard-card mb-4" style="background: linear-gradient(135deg, var(--grenadine) 0%, #c5362b 100%); color: white;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="mb-3">{{ $course->title }}</h1>
                        <p class="lead mb-4">{{ $course->short_description ?? $course->description }}</p>

                        <div class="course-meta">
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-clock"></i> {{ $course->duration }} недель
                            </span>
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-book"></i> {{ $course->lessons_count }} уроков
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-signal"></i> {{ $course->level }}
                            </span>
                        </div>
                    </div>

                    @if(auth()->check())
                        @if($progress)
                            <div class="text-end">
                                <div class="progress-card" style="background: rgba(255,255,255,0.9); color: #333; padding: 1rem; border-radius: 10px; min-width: 250px;">
                                    <h5 class="mb-3">Ваш прогресс</h5>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Общий прогресс</small>
                                            <small>{{ number_format($progress->overall_progress, 1) }}%</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" style="width: {{ $progress->overall_progress }}%; background: var(--grenadine);"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="fw-bold">{{ $progress->current_week }}</div>
                                                <small class="text-muted">Текущая неделя</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="fw-bold">{{ $progress->lessons_completed ?? 0 }}</div>
                                                <small class="text-muted">Уроков пройдено</small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($progress->estimated_completion)
                                        <div class="mt-2 text-center">
                                            <small class="text-muted">Завершение: {{ $progress->estimated_completion->format('d.m.Y') }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-end">
                                <form action="{{ route('learning.start', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-light btn-lg">
                                        <i class="fas fa-play-circle"></i> Начать обучение
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="text-end">
                            <a href="{{ route('login') }}" class="btn-light btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Войти для обучения
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Навигация по неделям -->
            <div class="week-navigation mb-4">
                <h3 class="mb-3">Быстрый переход по неделям</h3>
                <div class="week-buttons">
                    @for($i = 1; $i <= 12; $i += 4)
                        <div class="btn-group mb-2" role="group">
                            @for($j = $i; $j < $i + 4 && $j <= 80; $j++)
                                <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $j]) }}"
                                   class="btn-secondary-new">
                                    Неделя {{ $j }}
                                </a>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Модули курса -->
            <div class="modules-container">
                @foreach($course->modules as $module)
                    <div class="dashboard-card mb-4">
                        <div class="card-header" style="background: {{ $module->phase == 'basic' ? 'rgba(223, 63, 50, 0.1)' : ($module->phase == 'intermediate' ? 'rgba(125, 160, 202, 0.1)' : 'rgba(16, 185, 129, 0.1)') }};">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1">{{ $module->title }}</h3>
                                    <div class="module-info">
                                        <span class="badge bg-grenadine me-2">Недели {{ $module->start_week }}-{{ $module->end_week }}</span>
                                        <span class="badge bg-secondary">{{ $module->duration_weeks }} недель</span>
                                    </div>
                                </div>
                                <div>
                                    @if($module->phase == 'basic')
                                        <span class="badge bg-info" style="background: var(--grenadine) !important;">Начинающий</span>
                                    @elseif($module->phase == 'intermediate')
                                        <span class="badge bg-warning text-dark">Средний</span>
                                    @else
                                        <span class="badge bg-success">Продвинутый</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <p class="card-text">{{ $module->description }}</p>

                            @if($module->learning_objectives && is_array(json_decode($module->learning_objectives, true)))
                                <div class="learning-objectives mt-3">
                                    <h5>Цели модуля:</h5>
                                    <ul>
                                        @foreach(json_decode($module->learning_objectives, true) as $objective)
                                            <li>{{ $objective }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Пример уроков из модуля -->
                            <div class="module-preview mt-4">
                                <h5>Примеры уроков:</h5>
                                <div class="row">
                                    @php
                                        $sampleLessons = $module->lessons()->take(3)->get();
                                    @endphp

                                    @if($sampleLessons->count() > 0)
                                        @foreach($sampleLessons as $lesson)
                                            <div class="col-md-4 mb-3">
                                                <div class="dashboard-card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <span class="badge bg-light text-dark">
                                                                {{ $lesson->lesson_type_label ?? $lesson->lesson_type }}
                                                            </span>
                                                            <small class="text-muted">{{ $lesson->estimated_time }} мин</small>
                                                        </div>
                                                        <h6 class="card-title">{{ $lesson->title }}</h6>
                                                        <p class="card-text small text-muted">{{ Str::limit(strip_tags($lesson->content), 100) }}</p>
                                                    </div>
                                                    <div class="card-footer bg-transparent">
                                                        <a href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $lesson->id]) }}"
                                                           class="btn-secondary-new btn-sm w-100">
                                                            Посмотреть урок
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                Уроки для этого модуля будут добавлены в ближайшее время.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $module->start_week]) }}"
                                   class="btn-primary-new">
                                    <i class="fas fa-play"></i> Начать модуль (неделя {{ $module->start_week }})
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Прогресс по неделям -->
            @if($progress)
                <div class="weekly-progress mt-5">
                    <h3 class="mb-4">Прогресс по неделям</h3>
                    <div class="row">
                        @for($week = 1; $week <= min(12, $progress->total_weeks); $week++)
                            <div class="col-md-3 col-6 mb-3">
                                <div class="dashboard-card h-100">
                                    <div class="card-body text-center">
                                        <div class="week-number mb-2" style="font-size: 2rem; color: var(--grenadine);">
                                            {{ $week }}
                                        </div>
                                        <h6 class="card-title">Неделя {{ $week }}</h6>

                                        @php
                                            $weekLessons = $course->lessons()->where('week_number', $week)->count();
                                            $completedWeekLessons = auth()->check() ?
                                                auth()->user()->userLessons()
                                                    ->whereHas('lesson', function($query) use ($course, $week) {
                                                        $query->where('course_id', $course->id)
                                                              ->where('week_number', $week);
                                                    })
                                                    ->where('completed', true)
                                                    ->count() : 0;

                                            $percentage = $weekLessons > 0 ? ($completedWeekLessons / $weekLessons) * 100 : 0;
                                        @endphp

                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar" style="width: {{ $percentage }}%; background: var(--grenadine);"></div>
                                        </div>
                                        <small class="text-muted">{{ $completedWeekLessons }}/{{ $weekLessons }} уроков</small>

                                        <div class="mt-2">
                                            @if($weekLessons > 0)
                                                <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $week]) }}"
                                                   class="btn-sm {{ $percentage == 100 ? 'btn-primary-new' : 'btn-secondary-new' }}">
                                                    {{ $percentage == 100 ? 'Повторить' : 'Начать' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Успех!',
                    text: '{{ session('success') }}',
                    timer: 3000
                });
            });
        </script>
    @endif
@endsection
