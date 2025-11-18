@extends('app')

@section('title', 'Результаты теста - Knowly')

@section('content')
    <div class="results-hero">
        <div class="profile-card results-card">
            <div class="results-header">
                <div class="results-trophy">
                    <i class="fas fa-trophy"></i>
                </div>
                <h1 class="results-title">Результаты теста</h1>
                <p class="results-subtitle">Мы определили ваш уровень английского языка</p>
            </div>

            <div class="results-main">
                <div class="level-badge-large">
                    {{ $levelTitles[$test->level] ?? $test->level }}
                </div>

                <div class="results-stats">
                    <div class="result-stat">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $test->correct_answers }}/{{ $test->total_questions }}</div>
                        <div class="stat-label">Правильных ответов</div>
                    </div>

                    <div class="result-stat">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-value">{{ $test->percentage }}%</div>
                        <div class="stat-label">Общий результат</div>
                    </div>

                    <div class="result-stat">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-value">{{ $test->score }}</div>
                        <div class="stat-label">Набранные баллы</div>
                    </div>
                </div>

                <div class="level-progress">
                    <div class="progress-visual">
                        <div class="progress-circle">
                            <div class="circle-bg"></div>
                            <div class="circle-fill" style="transform: rotate({{ ($test->percentage / 100) * 360 + 45 }}deg);"></div>
                        </div>
                        <div class="progress-info-detailed">
                            <h4>Ваш прогресс по уровням</h4>
                            <p>Текущий уровень: <strong>{{ $levelTitles[$test->level] ?? $test->level }}</strong></p>
                            <p>Следующий уровень: <strong>{{ $nextLevel ?? 'Продолжайте в том же духе!' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($recommendedCourses))
                <div class="recommended-courses">
                    <h3 class="section-title">Рекомендованные курсы</h3>
                    <div class="courses-grid-improved">
                        @foreach($recommendedCourses as $course)
                            <div class="course-card-improved">
                                <div class="course-header-improved">
                                    <div class="course-icon-improved">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <h3>{{ $course['title'] }}</h3>
                                    <div class="course-level-improved">{{ $course['level'] }}</div>
                                </div>
                                <div class="course-content-improved">
                                    <h4>{{ $course['title'] }}</h4>
                                    <p>{{ $course['description'] }}</p>
                                    <a href="{{ $course['link'] }}" class="btn btn-primary" style="width: 100%; text-align: center;">
                                        <i class="fas fa-play-circle"></i>
                                        Начать обучение
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="results-actions">
                <a href="/courses" class="btn btn-primary">
                    <i class="fas fa-book-open"></i>
                    Смотреть все курсы
                </a>
                <a href="/dashboard" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    В личный кабинет
                </a>
            </div>
        </div>
    </div>
@endsection
