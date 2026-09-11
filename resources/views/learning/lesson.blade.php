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
                        <a href="{{ route('learning.plan', ['courseSlug' => $lesson->course->slug]) }}">Учебный план</a>
                    </li>
                    @if($lesson->module)
                        <li class="breadcrumb-item">
                            <a href="{{ route('learning.week', ['courseSlug' => $lesson->course->slug, 'week' => $lesson->module->order_index]) }}">
                                Модуль {{ $lesson->module->order_index }}
                            </a>
                        </li>
                    @endif
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
                                        @if($lesson->order_index <= 2 || $lesson->is_free)
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
                            @php
                                $learningMaterials = $lesson->learning_materials;
                                if (is_string($learningMaterials)) {
                                    $learningMaterials = json_decode($learningMaterials, true);
                                }
                            @endphp

                            @if($learningMaterials && is_array($learningMaterials) && count($learningMaterials) > 0)
                                <div class="lesson-materials mt-4">
                                    <h4><i class="fas fa-download"></i> Материалы урока</h4>
                                    <div class="list-group">
                                        @foreach($learningMaterials as $material)
                                            @if(is_array($material))
                                                <a href="{{ $material['url'] ?? '#' }}" class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-file-pdf me-2"></i>
                                                            {{ $material['title'] ?? 'Материал' }}
                                                        </div>
                                                        <small class="text-muted">Скачать</small>
                                                    </div>
                                                </a>
                                            @else
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-file me-2"></i>
                                                            {{ $material }}
                                                        </div>
                                                        <small class="text-muted">Материал</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <!-- Навигация -->
                                <div>
                                    @if($prevLesson)
                                        <a href="{{ route('learning.lesson', ['courseSlug' => $lesson->course->slug, 'lesson' => $prevLesson->id]) }}"
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
                                        <a href="{{ route('learning.lesson', ['courseSlug' => $lesson->course->slug, 'lesson' => $nextLesson->id]) }}"
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
                                @if($lesson->module)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Модуль
                                        <span class="badge bg-info">Модуль {{ $lesson->module->order_index }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Порядковый номер
                                    <span class="badge bg-info">Урок {{ $lesson->order }}</span>
                                </li>
                                @if($lesson->module)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Модуль
                                        <span>{{ $lesson->module->title }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Тип урока
                                    <span class="badge bg-secondary">{{ $lesson->lesson_type_label ?? $lesson->lesson_type }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Время
                                    <span>{{ $lesson->estimated_time }} минут</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Дополнительные ресурсы -->
                    @php
                        $additionalResources = $lesson->additional_resources;
                        if (is_string($additionalResources)) {
                            $additionalResources = json_decode($additionalResources, true);
                        }
                    @endphp

                    @if($additionalResources && is_array($additionalResources) && count($additionalResources) > 0)
                        <div class="dashboard-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Дополнительные ресурсы</h5>
                            </div>
                            <div class="card-body">
                                @if(isset($additionalResources['links']) && is_array($additionalResources['links']))
                                    <h6>Полезные ссылки:</h6>
                                    <ul class="list-unstyled">
                                        @foreach($additionalResources['links'] as $link)
                                            <li class="mb-2">
                                                <a href="{{ $link['url'] }}" target="_blank" class="text-decoration-none">
                                                    <i class="fas fa-external-link-alt me-2"></i>{{ $link['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if(isset($additionalResources['recommendations']) && is_array($additionalResources['recommendations']))
                                    <h6 class="mt-3">Рекомендации:</h6>
                                    <ul>
                                        @foreach($additionalResources['recommendations'] as $recommendation)
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
                                    <a href="{{ route('learning.plan', ['courseSlug' => $lesson->course->slug]) }}"
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
