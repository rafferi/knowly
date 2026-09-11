@extends('app')

@section('title', $test->title . ' - Knowly')

@section('content')
    <div class="lesson-container">
        <div class="container" style="padding-top: 100px;">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('universal-tests.index') }}">Тесты</a></li>
                    <li class="breadcrumb-item active">{{ $test->title }}</li>
                </ol>
            </nav>

            <div class="test-header-content">
                <div class="test-badge-large test-{{ $test->type }}">
                    {{ $test->type_label }}
                </div>

                <h1>{{ $test->title }}</h1>
                <p class="test-description">{{ $test->description }}</p>

                @if($test->course)
                    <div class="course-link">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Курс: {{ $test->course->title }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Информация о тесте</h3>
                    </div>
                    <div class="card-body">
                        <div class="test-info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Время на выполнение</div>
                                    <div class="info-value">{{ $test->estimated_time }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Количество вопросов</div>
                                    <div class="info-value">{{ $test->total_questions }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Проходной балл</div>
                                    <div class="info-value">{{ $test->passing_score }}%</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-redo"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Максимум попыток</div>
                                    <div class="info-value">{{ $test->max_attempts }}</div>
                                </div>
                            </div>
                        </div>

                        @if($test->module)
                            <div class="module-info-card mt-4">
                                <div class="module-header">
                                    <i class="fas fa-book-open"></i>
                                    <h4>Связанный модуль</h4>
                                </div>
                                <div class="module-content">
                                    <h5>{{ $test->module->title }}</h5>
                                    <p>{{ $test->module->description }}</p>
                                    <div class="module-meta">
                                        <span class="badge bg-info">Недели {{ $test->module->start_week }}-{{ $test->module->end_week }}</span>
                                        <span class="badge bg-secondary">{{ $test->module->duration_weeks }} недель</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($test->type === 'placement')
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle"></i>
                                <strong>Placement Test</strong> - этот тест поможет определить ваш текущий уровень английского языка и порекомендовать подходящие курсы для обучения.
                            </div>
                        @endif
                    </div>
                </div>

                @if($attempts->count() > 0)
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="mb-0">Ваши попытки</h3>
                        </div>
                        <div class="card-body">
                            <div class="attempts-list">
                                @foreach($attempts as $attempt)
                                    <div class="attempt-item {{ $attempt->passed ? 'passed' : 'failed' }}">
                                        <div class="attempt-header">
                                            <div class="attempt-number">
                                                Попытка #{{ $attempt->attempt_number }}
                                            </div>
                                            <div class="attempt-date">
                                                {{ $attempt->completed_at ? $attempt->completed_at->format('d.m.Y H:i') : 'В процессе' }}
                                            </div>
                                        </div>

                                        <div class="attempt-content">
                                            @if($attempt->completed_at)
                                                <div class="attempt-results">
                                                    <div class="score">
                                                        <span class="score-label">Результат:</span>
                                                        <span class="score-value">{{ $attempt->score }}/{{ $test->total_points }}</span>
                                                        <span class="score-percentage">
                                                        @if($attempt->total_questions > 0)
                                                                {{ round(($attempt->correct_answers / $attempt->total_questions) * 100) }}%
                                                            @endif
                                                    </span>
                                                    </div>

                                                    <div class="result-status">
                                                        @if($attempt->passed)
                                                            <span class="badge bg-success"><i class="fas fa-check"></i> Пройдено</span>
                                                        @else
                                                            <span class="badge bg-danger"><i class="fas fa-times"></i> Не пройдено</span>
                                                        @endif
                                                    </div>

                                                    <div class="attempt-details">
                                                        <span><i class="fas fa-check-circle"></i> Правильно: {{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</span>
                                                        <span><i class="fas fa-clock"></i> Время: {{ floor($attempt->time_spent / 60) }}:{{ str_pad($attempt->time_spent % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="attempt-in-progress">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    <span>Тест в процессе</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($attempt->completed_at)
                                            <div class="attempt-actions">
                                                <a href="{{ route('universal-tests.results', $attempt->id) }}" class="btn btn-sm btn-outline">
                                                    <i class="fas fa-chart-bar"></i> Подробнее
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Начать тест</h3>
                    </div>
                    <div class="card-body text-center">
                        @if($bestResult)
                            <div class="best-result mb-4">
                                <div class="best-score">
                                    Ваш лучший результат
                                </div>
                                <div class="best-value">
                                    {{ $bestResult->score }}/{{ $test->total_points }}
                                </div>
                                <div class="best-percentage">
                                    @if($bestResult->total_questions > 0)
                                        {{ round(($bestResult->correct_answers / $bestResult->total_questions) * 100) }}%
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="test-stats mb-4">
                            <div class="stat-item">
                                <div class="stat-number">{{ $attempts->count() }}</div>
                                <div class="stat-label">Завершено попыток</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ $test->max_attempts }}</div>
                                <div class="stat-label">Максимум попыток</div>
                            </div>
                        </div>

                        @if($canTakeTest)
                            <a href="{{ route('universal-tests.start', $test->id) }}" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-play-circle"></i>
                                @if($attempts->count() > 0)
                                    Пройти еще раз (Попытка #{{ $nextAttempt }})
                                @else
                                    Начать тест
                                @endif
                            </a>
                        @else
                            <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                                <i class="fas fa-ban"></i> Все попытки использованы
                            </button>
                        @endif

                        <div class="test-instructions">
                            <h5>Инструкции:</h5>
                            <ul>
                                <li>Не обновляйте страницу во время теста</li>
                                <li>Убедитесь, что у вас стабильное интернет-соединение</li>
                                <li>Время будет отсчитываться с момента начала теста</li>
                                @if($test->time_limit)
                                    <li>Тест автоматически завершится через {{ $test->time_limit }} минут</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                @if($test->week_number)
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="mb-0">Прогресс недели</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $userProgress = \App\Models\UserLearningProgress::where('user_id', Auth::id())
                                    ->where('course_id', $test->course_id)
                                    ->first();
                            @endphp

                            @if($userProgress)
                                <div class="week-progress">
                                    <div class="week-info">
                                        <div class="week-current">
                                            Текущая неделя: {{ $userProgress->current_week }}
                                        </div>
                                        <div class="week-test">
                                            Тест недели: {{ $test->week_number }}
                                        </div>
                                    </div>

                                    @if($test->week_number <= $userProgress->current_week)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            Вы можете пройти этот тест
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Для доступа к тесту нужно достичь {{ $test->week_number }} недели
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Начните обучение на курсе, чтобы получить доступ к тестам
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .test-detail-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .test-detail-header {
            margin-bottom: 3rem;
        }

        .test-header-content {
            text-align: center;
        }

        .test-badge-large {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .test-header-content h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .test-description {
            color: var(--ultimate-gray);
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto 1.5rem;
            line-height: 1.6;
        }

        .course-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(223, 63, 50, 0.1);
            border-radius: 20px;
            color: var(--grenadine);
        }

        .test-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: var(--grenadine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--black-beauty);
        }

        .module-info-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            border: 2px solid var(--nimbus-cloud);
        }

        .module-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .module-header i {
            color: var(--grenadine);
            font-size: 1.5rem;
        }

        .module-header h4 {
            margin: 0;
        }

        .module-content h5 {
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .module-content p {
            color: var(--ultimate-gray);
            margin-bottom: 1rem;
        }

        .module-meta {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .attempts-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .attempt-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 5px solid;
        }

        .attempt-item.passed {
            border-left-color: #28a745;
        }

        .attempt-item.failed {
            border-left-color: #dc3545;
        }

        .attempt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .attempt-number {
            font-weight: 600;
            color: var(--black-beauty);
        }

        .attempt-date {
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .attempt-results {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: center;
        }

        .score {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .score-label {
            color: var(--ultimate-gray);
        }

        .score-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--grenadine);
        }

        .score-percentage {
            font-size: 1rem;
            color: var(--ultimate-gray);
        }

        .attempt-details {
            grid-column: 1 / -1;
            display: flex;
            gap: 1.5rem;
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .attempt-in-progress {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--grenadine);
            font-weight: 600;
        }

        .attempt-actions {
            margin-top: 1rem;
            text-align: right;
        }

        .best-result {
            text-align: center;
            padding: 1rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
        }

        .best-score {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
            margin-bottom: 0.5rem;
        }

        .best-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--grenadine);
            margin-bottom: 0.25rem;
        }

        .best-percentage {
            font-size: 1rem;
            color: var(--ultimate-gray);
        }

        .test-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            text-align: center;
        }

        .stat-item {
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--grenadine);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--ultimate-gray);
        }

        .test-instructions {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--nimbus-cloud);
        }

        .test-instructions h5 {
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .test-instructions ul {
            margin: 0;
            padding-left: 1.2rem;
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .test-instructions li {
            margin-bottom: 0.25rem;
        }

        .week-progress {
            text-align: center;
        }

        .week-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: var(--gray-light);
            border-radius: 8px;
        }

        .week-current, .week-test {
            font-size: 0.9rem;
            color: var(--black-beauty);
        }

        @media (max-width: 768px) {
            .test-info-grid {
                grid-template-columns: 1fr;
            }

            .attempt-results {
                grid-template-columns: 1fr;
            }

            .week-info {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endsection
