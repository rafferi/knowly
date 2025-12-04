@extends('app')

@section('title', 'Домашнее задание')

@section('content')
    <div class="dashboard-hero" style="min-height: 100vh; padding-top: 120px;">
        <div class="container">
            <!-- Навигация -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('homeworks.index') }}">Домашние задания</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($homework->lesson->title ?? 'Задание', 30) }}</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Информация о задании -->
                    <div class="dashboard-card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Домашнее задание</h3>
                            <div>
                                @switch($homework->status)
                                    @case('submitted')
                                        <span class="badge bg-warning">Отправлено на проверку</span>
                                        @break
                                    @case('graded')
                                        <span class="badge bg-success">Проверено</span>
                                        @break
                                    @case('returned_for_revision')
                                        <span class="badge bg-danger">Требует доработки</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">В работе</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h5>Урок: {{ $homework->lesson->title ?? 'Урок удален' }}</h5>
                                <p class="text-muted">
                                    Курс: {{ $homework->course->title ?? '' }} |
                                    Модуль: {{ $homework->module->title ?? '' }}
                                </p>
                            </div>

                            <!-- Ответ студента -->
                            <div class="mb-4">
                                <h5>Ваш ответ:</h5>
                                <div class="answer-content p-3 bg-light rounded">
                                    {!! nl2br(e($homework->content)) !!}
                                </div>
                            </div>

                            <!-- Вложения -->
                            @if($homework->attachments && count($homework->attachments) > 0)
                                <div class="mb-4">
                                    <h5>Прикрепленные файлы:</h5>
                                    <div class="attachments-list">
                                        @foreach($homework->attachments as $attachment)
                                            <div class="attachment-item p-2 border rounded mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-paperclip me-2"></i>
                                                        {{ $attachment['name'] }}
                                                        <small class="text-muted ms-2">({{ round($attachment['size'] / 1024) }} KB)</small>
                                                    </div>
                                                    <a href="{{ Storage::url($attachment['path']) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       target="_blank">
                                                        Скачать
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Оценка преподавателя -->
                            @if($homework->status === 'graded')
                                <div class="grading-section p-3 bg-success bg-opacity-10 rounded">
                                    <h5><i class="fas fa-star text-success"></i> Оценка преподавателя</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="score-display text-center">
                                                <div class="score-value" style="font-size: 3rem; color: var(--grenadine);">
                                                    {{ $homework->score }}
                                                </div>
                                                <div class="score-max">из {{ $homework->max_score }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <h6>Комментарий:</h6>
                                            <p class="feedback-content">{{ $homework->teacher_feedback }}</p>

                                            @if($homework->corrections && count($homework->corrections) > 0)
                                                <h6 class="mt-3">Исправления:</h6>
                                                <ul>
                                                    @foreach($homework->corrections as $correction)
                                                        <li>{{ $correction }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Требует доработки -->
                            @if($homework->status === 'returned_for_revision')
                                <div class="revision-section p-3 bg-warning bg-opacity-10 rounded">
                                    <h5><i class="fas fa-exclamation-triangle text-warning"></i> Требует доработки</h5>
                                    <p><strong>Комментарий преподавателя:</strong></p>
                                    <p>{{ $homework->teacher_feedback }}</p>
                                    <a href="{{ route('homeworks.edit', $homework) }}"
                                       class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Исправить задание
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="homework-meta d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">
                                        Отправлено: {{ $homework->submitted_at ? $homework->submitted_at->format('d.m.Y H:i') : 'не отправлено' }}
                                    </small>
                                </div>
                                <div>
                                    @if($homework->reviewed_at)
                                        <small class="text-muted">
                                            Проверено: {{ $homework->reviewed_at->format('d.m.Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Сайдбар -->
                <div class="col-lg-4">
                    <!-- Действия -->
                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Действия</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($homework->status !== 'graded')
                                    <a href="{{ route('homeworks.edit', $homework) }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Редактировать
                                    </a>
                                @endif

                                <a href="{{ route('learning.lesson', ['course' => $homework->course->slug, 'lesson' => $homework->lesson_id]) }}"
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-book"></i> Перейти к уроку
                                </a>

                                @if(Auth::user()->isTeacher() && $homework->status === 'submitted')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#gradeModal">
                                        <i class="fas fa-star"></i> Оценить
                                    </button>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revisionModal">
                                        <i class="fas fa-redo"></i> Вернуть на доработку
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Информация -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">Информация</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Статус</span>
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
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Балл</span>
                                    <span>{{ $homework->score ? $homework->score . '/' . $homework->max_score : 'Не оценено' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Дедлайн</span>
                                    <span>{{ $homework->deadline_at ? $homework->deadline_at->format('d.m.Y') : 'Нет' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Время выполнения</span>
                                    <span>{{ $homework->time_spent ? $homework->time_spent . ' мин' : 'Не указано' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для оценки -->
    @if(Auth::user()->isTeacher())
        <div class="modal fade" id="gradeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Оценить домашнее задание</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('homeworks.grade', $homework) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Балл (максимум {{ $homework->max_score }})</label>
                                <input type="number" name="score" class="form-control"
                                       min="0" max="{{ $homework->max_score }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Комментарий</label>
                                <textarea name="teacher_feedback" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Исправления (по одному в строке)</label>
                                <textarea name="corrections[]" class="form-control" rows="3"
                                          placeholder="Ошибка 1&#10;Ошибка 2&#10;..."></textarea>
                                <small class="text-muted">Каждое исправление с новой строки</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-success">Оценить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Модальное окно для возврата на доработку -->
        <div class="modal fade" id="revisionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Вернуть на доработку</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('homeworks.return', $homework) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Причина возврата и комментарии</label>
                                <textarea name="feedback" class="form-control" rows="5" required
                                          placeholder="Укажите, что нужно исправить..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-warning">Вернуть на доработку</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
