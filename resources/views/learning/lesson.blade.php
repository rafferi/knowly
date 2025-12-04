@extends('app')

@section('title', $lesson->title)

@section('content')
    <div class="dashboard-hero" style="min-height: 100vh; padding-top: 120px;">
        <div class="container">
            <!-- Навигация -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('courses.show', $lesson->course->slug) }}">{{ $lesson->course->title }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('learning.plan', $lesson->course->slug) }}">Учебный план</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('learning.week', ['course' => $lesson->course->slug, 'week' => $lesson->week_number]) }}">
                            Неделя {{ $lesson->week_number }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($lesson->title, 30) }}</li>
                </ol>
            </nav>

            <!-- Урок -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Контент урока -->
                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0">{{ $lesson->title }}</h2>
                                    <div class="lesson-meta mt-2">
                                        <span class="badge bg-grenadine me-2">
                                            {{ $lesson->lesson_type_label ?? $lesson->lesson_type }}
                                        </span>
                                        <span class="text-muted me-3">
                                            <i class="fas fa-clock"></i> {{ $lesson->estimated_time }} мин
                                        </span>
                                        @if($lesson->week_number <= 2 || $lesson->is_free)
                                            <span class="badge bg-success">Бесплатный</span>
                                        @endif
                                    </div>
                                </div>

                                @if($userLesson && $userLesson->completed)
                                    <div class="text-end">
                                        <span class="badge bg-success" style="font-size: 1rem;">
                                            <i class="fas fa-check-circle"></i> Завершено
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Видео (если есть) -->
                            @if($lesson->video_url)
                                <div class="lesson-video mb-4">
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $lesson->video_url }}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endif

                            <!-- Контент урока -->
                            <div class="lesson-text">
                                {!! $lesson->content !!}
                            </div>

                            <!-- Материалы урока -->
                            @if($lesson->learning_materials && is_array(json_decode($lesson->learning_materials, true)))
                                <div class="lesson-materials mt-4">
                                    <h4><i class="fas fa-download"></i> Материалы урока</h4>
                                    <div class="list-group">
                                        @foreach(json_decode($lesson->learning_materials, true) as $material)
                                            <a href="{{ $material['url'] }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-file-{{ $material['type'] == 'pdf' ? 'pdf' : 'video' }} me-2"></i>
                                                        {{ $material['title'] }}
                                                    </div>
                                                    <small class="text-muted">Скачать</small>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Домашнее задание (инструкция) -->
                            @if($lesson->has_homework && $lesson->homework_instructions)
                                <div class="dashboard-card mt-4">
                                    <div class="card-header">
                                        <h4 class="mb-0"><i class="fas fa-tasks"></i> Домашнее задание</h4>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $instructions = json_decode($lesson->homework_instructions, true);
                                        @endphp

                                        @if(isset($instructions['tasks']) && is_array($instructions['tasks']))
                                            <ul class="mt-2">
                                                @foreach($instructions['tasks'] as $task)
                                                    <li>{{ $task }}</li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if(isset($instructions['deadline_days']))
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Срок выполнения: {{ $instructions['deadline_days'] }} дней
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Отправка домашнего задания -->
                            @if($lesson->has_homework)
                                @if(auth()->check())
                                    @if($userHomework)
                                        <div class="dashboard-card mt-4">
                                            <div class="card-header">
                                                <h4 class="mb-0"><i class="fas fa-tasks"></i> Ваше домашнее задание</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i> Вы уже отправили домашнее задание для этого урока.
                                                    <a href="{{ route('homeworks.show', $userHomework) }}" class="btn btn-sm btn-primary ms-2">
                                                        Посмотреть статус
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="dashboard-card mt-4">
                                            <div class="card-header">
                                                <h4 class="mb-0"><i class="fas fa-tasks"></i> Отправить домашнее задание</h4>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ route('homeworks.store', $lesson->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="content" class="form-label">Ваш ответ *</label>
                                                        <textarea name="content" id="content" class="form-control" rows="6"
                                                                  placeholder="Напишите ваш ответ здесь..." required></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="attachments" class="form-label">Прикрепить файлы</label>
                                                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                                        <small class="text-muted">Можно прикрепить несколько файлов (PDF, Word, изображения, аудио)</small>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-paper-plane"></i> Отправить на проверку
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="dashboard-card mt-4">
                                        <div class="card-header">
                                            <h4 class="mb-0"><i class="fas fa-tasks"></i> Домашнее задание</h4>
                                        </div>
                                        <div class="card-body">
                                            <p>Для отправки домашнего задания необходимо <a href="{{ route('login') }}">войти</a>.</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <!-- Навигация -->
                                <div>
                                    @if($prevLesson)
                                        <a href="{{ route('learning.lesson', ['course' => $lesson->course->slug, 'lesson' => $prevLesson->id]) }}"
                                           class="btn-secondary-new">
                                            <i class="fas fa-arrow-left"></i> Предыдущий урок
                                        </a>
                                    @endif
                                </div>

                                <!-- Действия -->
                                <div>
                                    @if(auth()->check())
                                        @if(!$userLesson || !$userLesson->completed)
                                            <form action="{{ route('learning.complete-lesson', $lesson->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="time_spent" value="{{ $lesson->estimated_time }}">
                                                <input type="hidden" name="score" value="100">

                                                <button type="submit" class="btn-primary-new">
                                                    <i class="fas fa-check-circle"></i> Завершить урок
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn-primary-new" disabled>
                                                <i class="fas fa-check-circle"></i> Урок завершен
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-primary-new">
                                            <i class="fas fa-sign-in-alt"></i> Войти для обучения
                                        </a>
                                    @endif
                                </div>

                                <div>
                                    @if($nextLesson)
                                        <a href="{{ route('learning.lesson', ['course' => $lesson->course->slug, 'lesson' => $nextLesson->id]) }}"
                                           class="btn-primary-new">
                                            Следующий урок <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Боковая панель -->
                <div class="col-lg-4">
                    <!-- Информация о курсе -->
                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Информация</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Курс
                                    <span class="badge bg-grenadine">{{ Str::limit($lesson->course->title, 20) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Неделя
                                    <span class="badge bg-info">Неделя {{ $lesson->week_number }}</span>
                                </li>
                                @if($lesson->module)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Модуль
                                        <span>{{ $lesson->module->title }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Тип урока
                                    <span class="badge bg-secondary">{{ $lesson->lesson_type_label }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Время
                                    <span>{{ $lesson->estimated_time }} минут</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Дополнительные ресурсы -->
                    @if($lesson->additional_resources && is_array(json_decode($lesson->additional_resources, true)))
                        <div class="dashboard-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Дополнительные ресурсы</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $resources = json_decode($lesson->additional_resources, true);
                                @endphp

                                @if(isset($resources['links']) && is_array($resources['links']))
                                    <h6>Полезные ссылки:</h6>
                                    <ul class="list-unstyled">
                                        @foreach($resources['links'] as $link)
                                            <li class="mb-2">
                                                <a href="{{ $link['url'] }}" target="_blank" class="text-decoration-none">
                                                    <i class="fas fa-external-link-alt me-2"></i>{{ $link['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if(isset($resources['recommendations']) && is_array($resources['recommendations']))
                                    <h6 class="mt-3">Рекомендации:</h6>
                                    <ul>
                                        @foreach($resources['recommendations'] as $recommendation)
                                            <li class="mb-1"><small>{{ $recommendation }}</small></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Прогресс курса -->
                    @if(auth()->check())
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="mb-0">Ваш прогресс</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $totalLessons = $lesson->course->lessons()->count();
                                    $completedLessons = auth()->user()->userLessons()
                                        ->whereHas('lesson', function($query) use ($lesson) {
                                            $query->where('course_id', $lesson->course_id);
                                        })
                                        ->where('completed', true)
                                        ->count();

                                    $percentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                                @endphp

                                <div class="text-center mb-3">
                                    <div class="display-4 fw-bold" style="color: var(--grenadine);">{{ number_format($percentage, 1) }}%</div>
                                    <small class="text-muted">Общий прогресс</small>
                                </div>

                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar" style="width: {{ $percentage }}%; background: var(--grenadine);"></div>
                                </div>

                                <div class="text-center">
                                    <small>{{ $completedLessons }} из {{ $totalLessons }} уроков</small>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="{{ route('learning.plan', $lesson->course->slug) }}"
                                       class="btn-secondary-new btn-sm">
                                        <i class="fas fa-chart-line"></i> Подробная статистика
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
