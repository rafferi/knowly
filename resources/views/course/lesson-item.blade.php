@extends('app')

@section('title', $item->title . ' - ' . $course->title)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Навигация</h5>
                        <div class="d-grid gap-2">
                            @if($prevItem)
                                <a href="{{ route('course.sequence.item', [
                                'course' => $course->slug,
                                'itemType' => $prevItem['type'],
                                'itemId' => $prevItem['id']
                            ]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Назад
                                </a>
                            @endif

                            <a href="{{ route('course.sequence.index', $course->slug) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-1"></i> К последовательности
                            </a>

                            @if($nextItem)
                                <a href="{{ route('course.sequence.item', [
                                'course' => $course->slug,
                                'itemType' => $nextItem['type'],
                                'itemId' => $nextItem['id']
                            ]) }}" class="btn btn-primary">
                                    Далее <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Информация об уроке</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-clock text-muted me-2"></i>
                                {{ $item->duration_formatted }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-layer-group text-muted me-2"></i>
                                {{ $item->module->title ?? 'Без модуля' }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar text-muted me-2"></i>
                                Неделя {{ $item->week_number }}
                            </li>
                            @if($userLesson && $userLesson->completed)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Завершен: {{ $userLesson->completed_at->format('d.m.Y') }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-white">
                        <h2 class="mb-0">{{ $item->title }}</h2>
                        @if($item->description)
                            <p class="text-muted mb-0">{{ $item->description }}</p>
                        @endif
                    </div>

                    <div class="card-body">
                        @if($item->video_url)
                            <div class="ratio ratio-16x9 mb-4">
                                <iframe src="{{ $item->video_url }}" allowfullscreen></iframe>
                            </div>
                        @endif

                        <div class="lesson-content">
                            @php
                                // Добавляем кнопки воспроизведения для букв английского алфавита
                                $contentWithAudio = $item->content;

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

                        @if($item->materials && count($item->materials) > 0)
                            <div class="mt-4">
                                <h5>Материалы урока</h5>
                                <div class="list-group">
                                    @foreach($item->materials as $material)
                                        <a href="{{ $material['url'] }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-file-{{ $material['type'] === 'pdf' ? 'pdf' : 'word' }} me-2"></i>
                                            {{ $material['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <div>
                                @if($userLesson && $userLesson->completed)
                                    <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i> Урок завершен
                                </span>
                                @endif
                            </div>

                            <div>
                                @if(!$userLesson || !$userLesson->completed)
                                    <form action="{{ route('course.sequence.complete-lesson', [
                                    'course' => $course->slug,
                                    'lesson' => $item->id
                                ]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i> Завершить урок
                                        </button>
                                    </form>
                                @elseif($nextItem)
                                    <a href="{{ route('course.sequence.item', [
                                    'course' => $course->slug,
                                    'itemType' => $nextItem['type'],
                                    'itemId' => $nextItem['id']
                                ]) }}" class="btn btn-primary">
                                        Продолжить <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .lesson-content {
            line-height: 1.6;
        }

        .lesson-content h1, .lesson-content h2, .lesson-content h3, .lesson-content h4 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .lesson-content p {
            margin-bottom: 1rem;
        }

        .lesson-content ul, .lesson-content ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
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
    </style>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
