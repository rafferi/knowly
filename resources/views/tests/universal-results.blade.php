@extends('app')

@section('title', 'Результаты теста - Knowly')

@section('content')
    <div class="test-results-container">
        <div class="results-header">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('universal-tests.index') }}">Тесты</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('universal-tests.show', $testResult->test_id) }}">{{ $testResult->test->title }}</a></li>
                    <li class="breadcrumb-item active">Результаты</li>
                </ol>
            </nav>

            <div class="results-title">
                <h1>Результаты теста</h1>
                <p>{{ $testResult->test->title }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Статистика попытки</h3>
                    </div>
                    <div class="card-body">
                        <div class="attempt-info">
                            <div class="attempt-meta">
                                <div class="meta-item">
                                    <i class="fas fa-hashtag"></i>
                                    <span>Попытка #{{ $testResult->attempt_number }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>Завершено: {{ $testResult->completed_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Время: {{ floor($testResult->time_spent / 60) }}:{{ str_pad($testResult->time_spent % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>

                            <div class="score-display">
                                <div class="score-circle {{ $testResult->passed ? 'passed' : 'failed' }}">
                                    <div class="score-value">
                                        @if($testResult->total_questions > 0)
                                            {{ round(($testResult->correct_answers / $testResult->total_questions) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </div>
                                    <div class="score-label">Результат</div>
                                </div>

                                <div class="score-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Набрано баллов</div>
                                        <div class="detail-value">{{ $testResult->score }}/{{ $testResult->test->total_points }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Правильных ответов</div>
                                        <div class="detail-value">{{ $testResult->correct_answers }}/{{ $testResult->total_questions }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Статус</div>
                                        <div class="detail-value">
                                            @if($testResult->passed)
                                                <span class="badge bg-success">Пройдено</span>
                                            @else
                                                <span class="badge bg-danger">Не пройдено</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Проходной балл</div>
                                        <div class="detail-value">{{ $testResult->test->passing_score }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="mb-0">Разбор ответов</h3>
                    </div>
                    <div class="card-body">
                        <div class="questions-review">
                            @foreach($questionsWithAnswers as $index => $item)
                                <div class="question-review {{ $item['is_correct'] ? 'correct' : 'incorrect' }}">
                                    <div class="question-header">
                                        <div class="question-number">
                                            Вопрос {{ $index + 1 }}
                                        </div>
                                        <div class="question-points">
                                            <i class="fas fa-star"></i>
                                            <span>{{ $item['points'] }} баллов</span>
                                            @if($item['is_correct'])
                                                <span class="badge bg-success">+{{ $item['points'] }} баллов</span>
                                            @else
                                                <span class="badge bg-danger">0 баллов</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="question-content">
                                        <p class="question-text">{{ $item['question'] }}</p>

                                        @if($item['type'] === 'multiple_choice')
                                            <div class="options-review">
                                                @foreach($item['options'] as $key => $option)
                                                    <div class="option-review
                                                    {{ $key == $item['correct_answer'] ? 'correct-answer' : '' }}
                                                    {{ $key == $item['user_answer'] && !$item['is_correct'] ? 'user-incorrect' : '' }}">
                                                        <div class="option-letter">
                                                            {{ chr(65 + $key) }}
                                                        </div>
                                                        <div class="option-text">
                                                            {{ $option }}
                                                        </div>
                                                        <div class="option-markers">
                                                            @if($key == $item['correct_answer'])
                                                                <span class="correct-marker">
                                                                <i class="fas fa-check-circle"></i> Правильный ответ
                                                            </span>
                                                            @endif
                                                            @if($key == $item['user_answer'] && !$item['is_correct'])
                                                                <span class="incorrect-marker">
                                                                <i class="fas fa-times-circle"></i> Ваш ответ
                                                            </span>
                                                            @endif
                                                            @if($key == $item['user_answer'] && $item['is_correct'])
                                                                <span class="correct-marker">
                                                                <i class="fas fa-check-circle"></i> Ваш правильный ответ
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($item['type'] === 'true_false')
                                            <div class="options-review">
                                                <div class="true-false-review">
                                                    <div class="tf-option {{ $item['correct_answer'] === 'true' ? 'correct-answer' : '' }}">
                                                        <span class="tf-label">Верно</span>
                                                        @if($item['correct_answer'] === 'true')
                                                            <span class="correct-marker">
                                                            <i class="fas fa-check-circle"></i> Правильный ответ
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <div class="tf-option {{ $item['correct_answer'] === 'false' ? 'correct-answer' : '' }}">
                                                        <span class="tf-label">Неверно</span>
                                                        @if($item['correct_answer'] === 'false')
                                                            <span class="correct-marker">
                                                            <i class="fas fa-check-circle"></i> Правильный ответ
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($item['user_answer'])
                                                    <div class="user-answer">
                                                        Ваш ответ: <strong>{{ $item['user_answer'] === 'true' ? 'Верно' : 'Неверно' }}</strong>
                                                        @if($item['is_correct'])
                                                            <span class="badge bg-success">Правильно</span>
                                                        @else
                                                            <span class="badge bg-danger">Не правильно</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                        @elseif($item['type'] === 'short_answer')
                                            <div class="short-answer-review">
                                                <div class="correct-answer-box">
                                                    <strong>Правильный ответ:</strong>
                                                    <span>{{ $item['correct_answer'] }}</span>
                                                </div>
                                                @if($item['user_answer'])
                                                    <div class="user-answer-box {{ $item['is_correct'] ? 'correct' : 'incorrect' }}">
                                                        <strong>Ваш ответ:</strong>
                                                        <span>{{ $item['user_answer'] }}</span>
                                                        @if($item['is_correct'])
                                                            <span class="badge bg-success">Правильно</span>
                                                        @else
                                                            <span class="badge bg-danger">Не правильно</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="user-answer-box">
                                                        <strong>Ваш ответ:</strong>
                                                        <span class="no-answer">Не отвечено</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(isset($item['explanation']))
                                            <div class="explanation-box">
                                                <h5><i class="fas fa-lightbulb"></i> Объяснение:</h5>
                                                <p>{{ $item['explanation'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Итоговый результат</h3>
                    </div>
                    <div class="card-body">
                        <div class="final-result">
                            @if($testResult->passed)
                                <div class="result-success">
                                    <div class="success-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <h4>Поздравляем!</h4>
                                    <p>Вы успешно прошли тест</p>
                                </div>
                            @else
                                <div class="result-fail">
                                    <div class="fail-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <h4>Тест не пройден</h4>
                                    <p>Попробуйте еще раз</p>
                                </div>
                            @endif

                            <div class="result-stats">
                                <div class="stat-row">
                                    <span>Процент выполнения</span>
                                    <span class="stat-value">
                                    @if($testResult->total_questions > 0)
                                            {{ round(($testResult->correct_answers / $testResult->total_questions) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                </span>
                                </div>
                                <div class="stat-row">
                                    <span>Проходной процент</span>
                                    <span class="stat-value">{{ $testResult->test->passing_score }}%</span>
                                </div>
                                <div class="stat-row">
                                    <span>Время выполнения</span>
                                    <span class="stat-value">{{ floor($testResult->time_spent / 60) }}:{{ str_pad($testResult->time_spent % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="stat-row">
                                    <span>Дата прохождения</span>
                                    <span class="stat-value">{{ $testResult->completed_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Действия</h3>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            @if($testResult->test->type !== 'placement')
                                @php
                                    $attemptsCount = \App\Models\TestResult::where('user_id', Auth::id())
                                        ->where('test_id', $testResult->test_id)
                                        ->count();
                                @endphp

                                @if($attemptsCount < $testResult->test->max_attempts)
                                    <a href="{{ route('universal-tests.start', $testResult->test_id) }}" class="btn btn-primary w-100 mb-3">
                                        <i class="fas fa-redo"></i> Пройти тест еще раз
                                    </a>
                                @else
                                    <button class="btn btn-secondary w-100 mb-3" disabled>
                                        <i class="fas fa-ban"></i> Все попытки использованы
                                    </button>
                                @endif
                            @endif

                            <a href="{{ route('universal-tests.show', $testResult->test_id) }}" class="btn btn-outline w-100 mb-3">
                                <i class="fas fa-arrow-left"></i> Вернуться к тесту
                            </a>

                            <a href="{{ route('universal-tests.index') }}" class="btn btn-outline w-100 mb-3">
                                <i class="fas fa-list"></i> Все тесты
                            </a>

                                @if($testResult->test->course)
                                    <a href="{{ route('lectures.index', ['courseSlug' => $testResult->test->course->slug]) }}" class="btn btn-outline w-100">
                                    <i class="fas fa-graduation-cap"></i> К учебному плану
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if($testResult->test->type === 'placement')
                    <div class="dashboard-card">
                        <div class="card-header bg-grenadine text-white">
                            <h3 class="mb-0">Ваш уровень</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $percentage = $testResult->total_questions > 0 ?
                                    ($testResult->correct_answers / $testResult->total_questions) * 100 : 0;

                                if ($percentage < 20) $level = 'beginner';
                                elseif ($percentage < 40) $level = 'elementary';
                                elseif ($percentage < 60) $level = 'intermediate';
                                elseif ($percentage < 80) $level = 'upper_intermediate';
                                else $level = 'advanced';

                                $levelTitles = [
                                    'beginner' => 'Начинающий (A1)',
                                    'elementary' => 'Элементарный (A2)',
                                    'intermediate' => 'Средний (B1)',
                                    'upper_intermediate' => 'Выше среднего (B2)',
                                    'advanced' => 'Продвинутый (C1)',
                                    'master' => 'Мастер (C2)'
                                ];
                            @endphp

                            <div class="level-display">
                                <div class="level-badge level-{{ $level }}">
                                    {{ $levelTitles[$level] ?? $level }}
                                </div>
                                <p class="mt-3">На основе ваших результатов мы рекомендуем курсы уровня {{ strtoupper($level) }}</p>
                            </div>

                            <a href="{{ route('courses') }}?level={{ $level }}" class="btn btn-primary w-100 mt-3">
                                <i class="fas fa-search"></i> Найти подходящие курсы
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .test-results-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .results-header {
            margin-bottom: 3rem;
        }

        .results-title {
            text-align: center;
        }

        .results-title h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .results-title p {
            color: var(--ultimate-gray);
            font-size: 1.1rem;
        }

        .attempt-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .attempt-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--grenadine);
        }

        .score-display {
            display: flex;
            align-items: center;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 8px solid;
        }

        .score-circle.passed {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }

        .score-circle.failed {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .score-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--black-beauty);
        }

        .score-label {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
            margin-top: 0.5rem;
        }

        .score-details {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .detail-label {
            font-size: 0.8rem;
            color: var(--ultimate-gray);
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--black-beauty);
        }

        .questions-review {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .question-review {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 5px solid;
        }

        .question-review.correct {
            border-left-color: #28a745;
            background: rgba(40, 167, 69, 0.05);
        }

        .question-review.incorrect {
            border-left-color: #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--nimbus-cloud);
        }

        .question-number {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--black-beauty);
        }

        .question-points {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--grenadine);
            font-weight: 600;
        }

        .question-content {
            margin-top: 1rem;
        }

        .question-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: var(--black-beauty);
        }

        .options-review {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .option-review {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            background: white;
        }

        .option-review.correct-answer {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }

        .option-review.user-incorrect {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .option-letter {
            width: 40px;
            height: 40px;
            background: var(--nimbus-cloud);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--black-beauty);
            flex-shrink: 0;
        }

        .option-text {
            flex: 1;
            font-size: 1rem;
            line-height: 1.5;
        }

        .option-markers {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            font-size: 0.8rem;
        }

        .correct-marker {
            color: #28a745;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .incorrect-marker {
            color: #dc3545;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .true-false-review {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .tf-option {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .tf-option.correct-answer {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }

        .tf-label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .user-answer {
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 10px;
            margin-top: 1rem;
        }

        .short-answer-review {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .correct-answer-box, .user-answer-box {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            border: 2px solid;
        }

        .correct-answer-box {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }

        .user-answer-box.correct {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }

        .user-answer-box.incorrect {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .user-answer-box .no-answer {
            color: var(--ultimate-gray);
            font-style: italic;
        }

        .explanation-box {
            margin-top: 1.5rem;
            padding: 1rem 1.5rem;
            background: #e3f2fd;
            border-radius: 10px;
            border-left: 5px solid #2196f3;
        }

        .explanation-box h5 {
            color: #0d47a1;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .explanation-box p {
            color: #1565c0;
            margin: 0;
            line-height: 1.6;
        }

        .final-result {
            text-align: center;
        }

        .result-success, .result-fail {
            padding: 2rem 1rem;
        }

        .success-icon, .fail-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .success-icon {
            color: #28a745;
        }

        .fail-icon {
            color: #dc3545;
        }

        .result-success h4, .result-fail h4 {
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .result-success p, .result-fail p {
            color: var(--ultimate-gray);
        }

        .result-stats {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        .stat-value {
            font-weight: 600;
            color: var(--grenadine);
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .level-display {
            text-align: center;
        }

        .level-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .level-beginner {
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            color: #721c24;
        }

        .level-elementary {
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            color: #0c5460;
        }

        .level-intermediate {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #856404;
        }

        .level-upper_intermediate {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #155724;
        }

        .level-advanced {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        @media (max-width: 768px) {
            .score-display {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }

            .score-details {
                width: 100%;
            }

            .question-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .true-false-review {
                flex-direction: column;
            }
        }
    </style>
@endsection
