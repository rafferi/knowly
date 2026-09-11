@extends('app')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
    @php
        $getMediaUrl = function($path) {
            if (empty($path)) return null;
            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }
            return asset($path);
        };
    @endphp

    <div class="lesson-container" style="padding-top: 100px;">
        <div class="lesson-header">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.show', $course->slug) }}">Курсы</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lectures.index', $course->slug) }}">Лекции</a></li>
                    <li class="breadcrumb-item active">{{ $lesson->title }}</li>
                </ol>
            </nav>

            <div class="lesson-meta-header">
                <div class="lesson-info">
                    <div class="lesson-badge {{ $lesson->lesson_type }}">
                        {{ $lesson->lesson_type_label }}
                    </div>
                    <h1>{{ $lesson->title }}</h1>
                    <p class="lesson-description">{{ $lesson->description }}</p>

                    <div class="lesson-stats">
                        <div class="stat-item">
                            <i class="fas fa-clock"></i>
                            <span>Длительность: {{ $lesson->duration_formatted }}</span>
                        </div>
                        @if($lesson->module)
                            <div class="stat-item">
                                <i class="fas fa-book-open"></i>
                                <span>Модуль: {{ $lesson->module->title }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lesson-actions">
                    @if(!$userLesson || !$userLesson->completed)
                        <button class="btn btn-success btn-lg complete-lesson-btn">
                            <i class="fas fa-check-circle"></i> Отметить как пройденный
                        </button>
                    @else
                        <div class="completed-badge">
                            <i class="fas fa-check-circle"></i>
                            <span>Пройдено {{ $userLesson->completed_at->format('d.m.Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="lesson-content-card">
                    <!-- Аудио -->
                    @if($lesson->audio_url)
                        <div class="audio-player-wrapper mb-4">
                            <div class="audio-player-card">
                                <div class="audio-icon">
                                    <i class="fas fa-headphones"></i>
                                </div>
                                <div class="audio-content">
                                    <h5>Аудио-лекция</h5>
                                    <audio controls controlsList="nodownload" style="width:100%;">
                                        <source src="{{ $getMediaUrl($lesson->audio_url) }}" type="audio/mpeg">
                                        Ваш браузер не поддерживает аудио.
                                    </audio>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Видео -->
                    @if($lesson->video_url)
                        <div class="video-container mb-4">
                            @php
                                $fullVideoUrl = $getMediaUrl($lesson->video_url);
                                $isExternal = Str::startsWith($lesson->video_url, ['http://', 'https://']);
                                $isYoutube = $isExternal && Str::contains($lesson->video_url, ['youtube.com', 'youtu.be']);
                            @endphp

                            @if($isYoutube)
                                @php
                                    parse_str(parse_url($lesson->video_url, PHP_URL_QUERY), $query);
                                    $videoId = $query['v'] ?? basename(parse_url($lesson->video_url, PHP_URL_PATH));
                                @endphp
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="video-wrapper">
                                    <video-js id="lesson-video" class="vjs-default-skin vjs-big-play-centered" controls
                                              data-lesson-id="{{ $lesson->id }}"
                                              data-course-slug="{{ $course->slug }}"
                                              @if($lesson->thumbnail_url) poster="{{ $getMediaUrl($lesson->thumbnail_url) }}" @endif
                                              preload="auto"
                                              style="width:100%; max-height:500px;">
                                        <source src="{{ $fullVideoUrl }}" type="video/mp4">
                                        <p class="vjs-no-js">
                                            Для просмотра видео включите JavaScript или используйте браузер, поддерживающий HTML5 видео.
                                        </p>
                                    </video-js>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Текст урока с кнопками озвучки букв -->
                    <div class="content-section">
                        <h3>Содержание урока</h3>
                        <div class="lesson-text-content">
                            @php
                                // Добавляем кнопки воспроизведения для букв английского алфавита
                                $contentWithAudio = $lesson->content;

                                // 1. Заголовки "Буква X"
                                $contentWithAudio = preg_replace_callback(
                                    '/<h2>Буква ([A-Z])<\/h2>/',
                                    function($matches) {
                                        $letter = $matches[1];
                                        return '<h2>Буква ' . $letter . ' <button class="play-letter" data-letter="' . $letter . '" aria-label="Прослушать произношение буквы ' . $letter . '">🔊</button></h2>';
                                    },
                                    $contentWithAudio
                                );

                                // 2. Отдельные заглавные буквы в тегах <strong>
                                $contentWithAudio = preg_replace_callback(
                                    '/<strong>([A-Z])<\/strong>/',
                                    function($matches) {
                                        $letter = $matches[1];
                                        return '<strong>' . $letter . '</strong> <button class="play-letter" data-letter="' . $letter . '" aria-label="Прослушать произношение буквы ' . $letter . '">🔊</button>';
                                    },
                                    $contentWithAudio
                                );
                            @endphp
                            {!! $contentWithAudio !!}
                        </div>
                    </div>

                    @if($lesson->learning_materials && is_array($lesson->learning_materials) && count($lesson->learning_materials) > 0)
                        <div class="materials-section">
                            <h3><i class="fas fa-paperclip"></i> Материалы урока</h3>
                            <div class="materials-list">
                                @foreach($lesson->learning_materials as $material)
                                    @if(is_array($material))
                                        <a href="{{ $material['url'] ?? '#' }}" class="material-item" target="_blank">
                                            <div class="material-icon">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div class="material-content">
                                                <h5>{{ $material['title'] ?? $material['name'] ?? 'Материал' }}</h5>
                                                <p>{{ $material['description'] ?? '' }}</p>
                                            </div>
                                            <div class="material-action">
                                                <i class="fas fa-download"></i>
                                            </div>
                                        </a>
                                    @else
                                        <div class="material-item">
                                            <div class="material-icon">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div class="material-content">
                                                <h5>{{ $material }}</h5>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-section">
                    <div class="course-progress-card mb-4">
                        <h4>Прогресс курса</h4>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $userCourse->progress ?? 0 }}%"
                                 aria-valuenow="{{ $userCourse->progress ?? 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ round($userCourse->progress ?? 0) }}%
                            </div>
                        </div>
                        <p class="mb-0">{{ $userCourse->current_lesson ?? 0 }} из {{ $course->lessons_count }} уроков пройдено</p>
                    </div>

                    <div class="navigation-card mb-4">
                        <h4>Навигация</h4>
                        <div class="nav-buttons">
                            @if($prevLesson)
                                <a href="{{ route('lectures.show', ['courseSlug' => $course->slug, 'lessonId' => $prevLesson->id]) }}"
                                   class="btn btn-outline w-100 mb-2">
                                    <i class="fas fa-arrow-left"></i> Предыдущий урок
                                </a>
                            @endif

                            @if($nextItem)
                                <form action="{{ route('lectures.complete', ['courseSlug' => $course->slug, 'lessonId' => $lesson->id]) }}"
                                      method="POST"
                                      id="next-item-form"
                                      style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="redirect_url" value="{{ $nextItem['url'] }}">
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        {{ $nextItem['type'] === 'test' ? 'Перейти к тесту' : 'Следующий урок' }} <i class="fas fa-arrow-right"></i>
                                    </button>
                                </form>
                            @else
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                    <p class="mb-0">Вы завершили все уроки!</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="lesson-stats-card mt-4">
                        <h4>Ваша статистика</h4>
                        <div class="stats-list">
                            <div class="stat-row">
                                <span>Статус:</span>
                                <span>
                                    @if($userLesson && $userLesson->completed)
                                        <span class="badge bg-success">Пройдено</span>
                                    @elseif($userLesson && $userLesson->started_at)
                                        <span class="badge bg-warning">В процессе</span>
                                    @else
                                        <span class="badge bg-secondary">Не начато</span>
                                    @endif
                                </span>
                            </div>
                            <div class="stat-row">
                                <span>Время изучения:</span>
                                <span>{{ $userLesson ? floor($userLesson->time_spent / 60) : 0 }} мин</span>
                            </div>
                            <div class="stat-row">
                                <span>Попыток:</span>
                                <span>{{ $userLesson ? $userLesson->attempts : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .lesson-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            padding-top: 100px;
        }

        .lesson-header {
            margin-bottom: 3rem;
        }

        .lesson-meta-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .lesson-info {
            flex: 1;
        }

        .lesson-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
            color: white;
        }

        .lesson-info h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .lesson-description {
            color: var(--ultimate-gray);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .lesson-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ultimate-gray);
        }

        .stat-item i {
            color: var(--grenadine);
        }

        .lesson-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .completed-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            background: rgba(40, 167, 69, 0.1);
            border-radius: 10px;
            color: #28a745;
            font-weight: 600;
        }

        .completed-badge i {
            font-size: 1.5rem;
        }

        .lesson-content-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .audio-player-wrapper {
            margin-bottom: 2rem;
        }

        .audio-player-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border-left: 5px solid var(--grenadine);
        }

        .audio-icon {
            width: 60px;
            height: 60px;
            background: var(--grenadine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .audio-content {
            flex: 1;
        }

        .audio-content h5 {
            margin-bottom: 0.75rem;
            color: var(--black-beauty);
        }

        .video-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .video-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #000;
            border-radius: 10px;
        }

        .video-wrapper video-js {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .video-js {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .content-section {
            margin-top: 2rem;
        }

        .content-section h3 {
            margin-bottom: 1.5rem;
            color: var(--black-beauty);
            font-size: 1.5rem;
        }

        .lesson-text-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--black-beauty);
        }

        .lesson-text-content p {
            margin-bottom: 1.5rem;
        }

        .lesson-text-content h1, .lesson-text-content h2, .lesson-text-content h3 {
            color: var(--grenadine);
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        /* Стили для кнопок озвучки букв */
        .play-letter {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            margin-left: 0.5rem;
            color: #007bff;
            transition: transform 0.2s, color 0.2s;
            padding: 0 0.25rem;
            vertical-align: middle;
        }
        .play-letter:hover {
            transform: scale(1.3);
            color: #0056b3;
        }
        .play-letter:active {
            transform: scale(0.9);
        }

        .materials-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid var(--nimbus-cloud);
        }

        .materials-section h3 {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
        }

        .materials-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .material-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: var(--gray-light);
            border-radius: 10px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .material-item:hover {
            background: rgba(223, 63, 50, 0.1);
            transform: translateX(10px);
        }

        .material-icon {
            width: 50px;
            height: 50px;
            background: var(--grenadine);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .material-content {
            flex: 1;
        }

        .material-content h5 {
            margin-bottom: 0.25rem;
            color: var(--black-beauty);
        }

        .material-content p {
            margin: 0;
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .material-action {
            color: var(--grenadine);
            font-size: 1.2rem;
        }

        .sidebar-section {
            position: sticky;
            top: 2rem;
        }

        .course-progress-card,
        .navigation-card,
        .lesson-stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .course-progress-card h4,
        .navigation-card h4,
        .lesson-stats-card h4 {
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .nav-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stats-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .stat-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .stat-row span:first-child {
            color: var(--ultimate-gray);
        }

        .stat-row span:last-child {
            font-weight: 600;
            color: var(--black-beauty);
        }

        @media (max-width: 992px) {
            .lesson-meta-header {
                flex-direction: column;
            }
            .lesson-actions {
                width: 100%;
            }
            .completed-badge {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .lesson-stats {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    @section('scripts')
        <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
        <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Инициализация Video.js, если плеер существует
                if (document.getElementById('lesson-video')) {
                    videojs('lesson-video', {
                        controls: true,
                        autoplay: false,
                        preload: 'auto'
                    });
                }

                // Кнопка завершения урока
                const completeBtn = document.querySelector('.complete-lesson-btn');
                if (completeBtn) {
                    completeBtn.addEventListener('click', function() {
                        if (confirm('Отметить урок как пройденный?')) {
                            fetch('{{ route("lectures.complete", ["courseSlug" => $course->slug, "lessonId" => $lesson->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ time_spent: 300, score: 100 })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) location.reload();
                                    else alert('Ошибка: ' + data.error);
                                });
                        }
                    });
                }

                // Озвучка букв
                document.querySelectorAll('.play-letter').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const letter = this.dataset.letter.toLowerCase();
                        const audio = new Audio('/Alphabet/' + letter + '.mp3');
                        audio.play().catch(err => console.error('Ошибка воспроизведения:', err));
                    });
                });
            });
        </script>
    @endsection
@endsection
