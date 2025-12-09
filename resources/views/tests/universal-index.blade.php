@extends('app')

@section('title', 'Тесты - Knowly')

@section('content')
    <div class="tests-container">
        <div class="tests-header">
            <h1>Тесты и проверки знаний</h1>
            <p>Пройдите тесты для проверки знаний и отслеживания прогресса</p>
        </div>

        <div class="tests-navigation">
            <div class="nav-buttons">
                <a href="#placement" class="nav-btn active" data-type="placement">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Placement Test</span>
                </a>
                <a href="#module" class="nav-btn" data-type="module_test">
                    <i class="fas fa-book"></i>
                    <span>Модульные тесты</span>
                </a>
                <a href="#weekly" class="nav-btn" data-type="weekly_test">
                    <i class="fas fa-calendar-week"></i>
                    <span>Недельные тесты</span>
                </a>
                <a href="#midterm" class="nav-btn" data-type="midterm">
                    <i class="fas fa-chart-line"></i>
                    <span>Midterm Tests</span>
                </a>
                <a href="#final" class="nav-btn" data-type="final">
                    <i class="fas fa-trophy"></i>
                    <span>Финальные тесты</span>
                </a>
                <a href="#practice" class="nav-btn" data-type="practice">
                    <i class="fas fa-dumbbell"></i>
                    <span>Практические тесты</span>
                </a>
            </div>
        </div>

        @foreach($tests as $type => $typeTests)
            <section id="{{ $type }}" class="test-section" style="{{ $type !== 'placement' ? 'display: none;' : '' }}">
                <h2 class="section-title">
                    @php
                        $titles = [
                            'placement' => '📊 Placement Test',
                            'module_test' => '📘 Модульные тесты',
                            'weekly_test' => '📅 Недельные тесты',
                            'midterm' => '🎯 Midterm Tests',
                            'final' => '🏆 Финальные тесты',
                            'practice' => '💪 Практические тесты'
                        ];
                    @endphp
                    {{ $titles[$type] ?? $type }}
                </h2>

                @if($type === 'placement')
                    <div class="placement-test-info">
                        <div class="info-card">
                            <h3>Тест для определения уровня</h3>
                            <p>Определите свой текущий уровень английского языка для выбора оптимальной программы обучения</p>
                            <div class="info-details">
                                <span><i class="fas fa-clock"></i> 45 минут</span>
                                <span><i class="fas fa-question-circle"></i> 5 вопросов</span>
                                <span><i class="fas fa-chart-bar"></i> Определение уровня A1-C2</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="tests-grid">
                    @foreach($typeTests as $test)
                        @php
                            $userResult = $userResults[$test->id] ?? null;
                            $bestScore = null;
                            if ($userResult) {
                                $bestResult = \App\Models\TestResult::where('user_id', Auth::id())
                                    ->where('test_id', $test->id)
                                    ->orderBy('score', 'desc')
                                    ->first();
                                $bestScore = $bestResult->score ?? null;
                            }
                        @endphp

                        <div class="test-card">
                            <div class="test-header">
                                <div class="test-badge test-{{ $test->type }}">
                                    {{ $test->type_label }}
                                </div>
                                @if($test->week_number)
                                    <div class="week-badge">
                                        Неделя {{ $test->week_number }}
                                    </div>
                                @endif
                            </div>

                            <div class="test-content">
                                <h3>{{ $test->title }}</h3>
                                <p class="test-description">{{ $test->description }}</p>

                                <div class="test-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $test->estimated_time }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-question-circle"></i>
                                        <span>{{ $test->total_questions }} вопросов</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-chart-line"></i>
                                        <span>{{ $test->passing_score }}% для прохождения</span>
                                    </div>
                                </div>

                                @if($test->module)
                                    <div class="module-info">
                                        <i class="fas fa-book-open"></i>
                                        <span>Модуль: {{ $test->module->title }}</span>
                                    </div>
                                @endif

                                @if($userResult)
                                    <div class="user-results">
                                        <div class="result-summary">
                                            <div class="score-display">
                                                <span class="score-label">Лучший результат:</span>
                                                <span class="score-value">{{ $bestScore ?? 0 }}/{{ $test->total_points }}</span>
                                                <span class="score-percentage">
                                                @if($test->total_points > 0)
                                                        {{ round(($bestScore / $test->total_points) * 100) }}%
                                                    @endif
                                            </span>
                                            </div>
                                            <div class="attempts">
                                                <i class="fas fa-redo"></i>
                                                <span>Попыток: {{ $test->testResults()->where('user_id', Auth::id())->count() }}/{{ $test->max_attempts }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="test-actions">
                                @if(!$userResult || $test->testResults()->where('user_id', Auth::id())->count() < $test->max_attempts)
                                    <a href="{{ route('universal-tests.start', $test->id) }}" class="btn btn-primary">
                                        @if($userResult)
                                            <i class="fas fa-redo"></i> Пройти снова
                                        @else
                                            <i class="fas fa-play"></i> Начать тест
                                        @endif
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban"></i> Попытки исчерпаны
                                    </button>
                                @endif

                                <a href="{{ route('universal-tests.show', $test->id) }}" class="btn btn-outline">
                                    <i class="fas fa-info-circle"></i> Подробнее
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <style>
        .tests-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .tests-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .tests-header h1 {
            font-size: 2.5rem;
            color: var(--black-beauty);
            margin-bottom: 0.5rem;
        }

        .tests-header p {
            color: var(--ultimate-gray);
            font-size: 1.1rem;
        }

        .tests-navigation {
            margin-bottom: 3rem;
            overflow-x: auto;
        }

        .nav-buttons {
            display: flex;
            gap: 0.5rem;
            padding-bottom: 1rem;
        }

        .nav-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: var(--white);
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            text-decoration: none;
            color: var(--black-beauty);
            min-width: 120px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .nav-btn:hover, .nav-btn.active {
            border-color: var(--grenadine);
            background: rgba(223, 63, 50, 0.05);
            color: var(--grenadine);
        }

        .nav-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-btn span {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .test-section {
            margin-bottom: 4rem;
            animation: fadeIn 0.5s ease;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--black-beauty);
        }

        .placement-test-info {
            margin-bottom: 2rem;
        }

        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            border-left: 5px solid var(--grenadine);
        }

        .info-card h3 {
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .info-card p {
            color: var(--ultimate-gray);
            margin-bottom: 1.5rem;
        }

        .info-details {
            display: flex;
            gap: 2rem;
            color: var(--ultimate-gray);
        }

        .info-details i {
            color: var(--grenadine);
            margin-right: 0.5rem;
        }

        .tests-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .test-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--nimbus-cloud);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .test-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .test-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: var(--nimbus-cloud);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .test-badge {
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .test-placement {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .test-module_test {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .test-weekly_test {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .test-midterm {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .test-final {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .test-practice {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: var(--black-beauty);
        }

        .week-badge {
            background: var(--grenadine);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .test-content {
            padding: 1.5rem;
            flex: 1;
        }

        .test-content h3 {
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
            color: var(--black-beauty);
        }

        .test-description {
            color: var(--ultimate-gray);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .test-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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

        .module-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem;
            background: rgba(223, 63, 50, 0.05);
            border-radius: 8px;
            margin-bottom: 1.5rem;
            color: var(--grenadine);
            font-size: 0.9rem;
        }

        .module-info i {
            font-size: 0.9rem;
        }

        .user-results {
            margin: 1.5rem 0;
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .result-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .score-display {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .score-label {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
        }

        .score-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--grenadine);
        }

        .score-percentage {
            font-size: 0.9rem;
            color: var(--ultimate-gray);
        }

        .attempts {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .test-actions {
            padding: 1.5rem;
            border-top: 1px solid var(--nimbus-cloud);
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            flex: 1;
            text-align: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav-buttons {
                flex-wrap: wrap;
            }

            .tests-grid {
                grid-template-columns: 1fr;
            }

            .info-details {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const navButtons = document.querySelectorAll('.nav-btn');
                const testSections = document.querySelectorAll('.test-section');

                navButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const type = this.getAttribute('data-type');

                        navButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        testSections.forEach(section => {
                            if (section.id === type) {
                                section.style.display = 'block';
                                section.scrollIntoView({ behavior: 'smooth' });
                            } else {
                                section.style.display = 'none';
                            }
                        });
                    });
                });

                const testCards = document.querySelectorAll('.test-card');
                testCards.forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                });
            });
        </script>
    @endsection
@endsection
