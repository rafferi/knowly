@extends('app')

@section('title', 'Неделя ' . $week . ' - ' . $course->title)

@section('content')
    <div class="dashboard-hero" style="min-height: 100vh; padding-top: 120px;">
        <div class="container">
            <!-- Навигация -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('learning.plan', $course->slug) }}">Учебный план</a>
                    </li>
                    <li class="breadcrumb-item active">Неделя {{ $week }}</li>
                </ol>
            </nav>

            <!-- Заголовок недели -->
            <div class="dashboard-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-2">Неделя {{ $week }}</h1>
                            @if($module)
                                <div class="module-info">
                                    <span class="badge bg-grenadine">{{ $module->title }}</span>
                                    <span class="text-muted ms-2">
                                        {{ $module->start_week }}-{{ $module->end_week }} неделя
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="week-navigation">
                            @if($week > 1)
                                <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $week - 1]) }}"
                                   class="btn-secondary-new me-2">
                                    <i class="fas fa-arrow-left"></i> Предыдущая неделя
                                </a>
                            @endif

                            @if($week < 80)
                                <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $week + 1]) }}"
                                   class="btn-primary-new">
                                    Следующая неделя <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Прогресс недели -->
                    @php
                        $totalLessons = $lessons->count();
                        $completedLessons = auth()->check() ?
                            auth()->user()->userLessons()
                                ->whereIn('lesson_id', $lessons->pluck('id'))
                                ->where('completed', true)
                                ->count() : 0;

                        $percentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                    @endphp

                    @if(auth()->check())
                        <div class="week-progress mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Прогресс недели</span>
                                <span>{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $percentage }}%; background: var(--grenadine) !important;"></div>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">{{ $completedLessons }} из {{ $totalLessons }} уроков завершено</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Уроки недели -->
            <div class="lessons-container">
                <h2 class="mb-4">Уроки этой недели</h2>

                @if($lessons->count() > 0)
                    <div class="lessons-list">
                        @foreach($lessons as $lesson)
                            @php
                                $isCompleted = auth()->check() && isset($userLessons[$lesson->id]) && $userLessons[$lesson->id]->completed;
                                $isFree = $lesson->is_free || (auth()->check() && auth()->user()->xp > 100);
                            @endphp

                            <div class="dashboard-card mb-3 {{ $isCompleted ? 'border-success' : '' }}"
                                 style="{{ $isCompleted ? 'background-color: #f8fff8;' : '' }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            <div class="lesson-order">
                                                {{ $lesson->order }}
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="d-flex align-items-start">
                                                @if($isCompleted)
                                                    <span class="badge bg-success me-2">
                                                        <i class="fas fa-check"></i> Завершено
                                                    </span>
                                                @endif

                                                @if(!$isFree && !$isCompleted)
                                                    <span class="badge bg-warning text-dark me-2">
                                                        <i class="fas fa-lock"></i> Премиум
                                                    </span>
                                                @endif

                                                <div>
                                                    <h5 class="mb-1">{{ $lesson->title }}</h5>
                                                    <div class="lesson-meta">
                                                        <span class="badge bg-light text-dark me-2">
                                                            {{ $lesson->lesson_type_label ?? $lesson->lesson_type }}
                                                        </span>
                                                        <span class="text-muted me-3">
                                                            <i class="fas fa-clock"></i> {{ $lesson->estimated_time }} мин
                                                        </span>
                                                        @if($lesson->has_homework)
                                                            <span class="text-primary">
                                                                <i class="fas fa-tasks"></i> Есть домашнее задание
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $lesson->id]) }}"
                                                   class="btn {{ $isCompleted ? 'btn-outline-success' : 'btn-primary-new' }}">
                                                    <i class="fas fa-play-circle"></i>
                                                    {{ $isCompleted ? 'Повторить' : 'Начать' }}
                                                </a>

                                                @if($isCompleted)
                                                    <button type="button" class="btn btn-success" disabled>
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Быстрое завершение (для авторизованных) -->
                                    @if(auth()->check() && !$isCompleted && $isFree)
                                        <div class="mt-3">
                                            <form action="{{ route('learning.complete-lesson', $lesson->id) }}" method="POST" class="quick-complete-form">
                                                @csrf
                                                <input type="hidden" name="time_spent" value="{{ $lesson->estimated_time }}">
                                                <input type="hidden" name="score" value="100">

                                                <button type="button" class="btn-secondary-new btn-sm quick-complete-btn">
                                                    <i class="fas fa-check"></i> Отметить как пройденное
                                                </button>

                                                <small class="text-muted ms-2">(если вы уже знаете этот материал)</small>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Уроки для этой недели пока не добавлены.
                    </div>
                @endif
            </div>

            <!-- Статистика недели -->
            @if($lessons->count() > 0)
                <div class="dashboard-card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Статистика недели</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-value" style="font-size: 2rem; color: var(--grenadine);">
                                        {{ $totalLessons }}
                                    </div>
                                    <div class="stat-label">Всего уроков</div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-value" style="font-size: 2rem; color: #10b981;">
                                        {{ $completedLessons }}
                                    </div>
                                    <div class="stat-label">Завершено</div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-value" style="font-size: 2rem; color: #f59e0b;">
                                        {{ $lessons->where('has_homework', true)->count() }}
                                    </div>
                                    <div class="stat-label">Домашних заданий</div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                @php
                                    $totalTime = $lessons->sum('estimated_time');
                                    $hours = floor($totalTime / 60);
                                    $minutes = $totalTime % 60;
                                @endphp
                                <div class="stat-item">
                                    <div class="stat-value" style="font-size: 2rem; color: #ef4444;">
                                        {{ $totalTime }}
                                    </div>
                                    <div class="stat-label">Минут обучения</div>
                                    @if($hours > 0)
                                        <small class="text-muted">({{ $hours }}ч {{ $minutes }}мин)</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Навигация между неделями -->
            <div class="week-navigation-footer mt-4">
                <div class="d-flex justify-content-between">
                    @if($week > 1)
                        <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $week - 1]) }}"
                           class="btn-secondary-new">
                            <i class="fas fa-arrow-left"></i> Неделя {{ $week - 1 }}
                        </a>
                    @else
                        <span></span>
                    @endif

                    <a href="{{ route('learning.plan', $course->slug) }}" class="btn-secondary-new">
                        <i class="fas fa-list"></i> Весь учебный план
                    </a>

                    @if($week < 80)
                        <a href="{{ route('learning.week', ['course' => $course->slug, 'week' => $week + 1]) }}"
                           class="btn-primary-new">
                            Неделя {{ $week + 1 }} <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Быстрое завершение уроков
            document.querySelectorAll('.quick-complete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    const lessonCard = this.closest('.dashboard-card');
                    const lessonTitle = lessonCard.querySelector('h5').textContent;

                    Swal.fire({
                        title: 'Завершить урок?',
                        text: `Вы уверены, что хотите отметить урок "${lessonTitle}" как пройденный?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Да, завершить',
                        cancelButtonText: 'Отмена'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    time_spent: form.querySelector('[name="time_spent"]').value,
                                    score: form.querySelector('[name="score"]').value
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Успех!',
                                            text: data.message,
                                            timer: 2000
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Ошибка',
                                        text: 'Что-то пошло не так'
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection
