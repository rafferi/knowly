@extends('app')

@section('title', 'Кабинет - Knowly')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <h1>Добро пожаловать, {{ $user->name }}!</h1>
                <p class="profile-subtitle">Ваш прогресс обучения</p>

                <div class="xp-progress">
                    <div class="xp-info">
                        <span>Уровень {{ $user->level_title }}</span>
                        <span>{{ $user->xp }} XP</span>
                    </div>
                    <div class="xp-bar">
                        <div class="xp-fill" style="width: {{ $user->level_progress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="profile-quick-actions">
                <a href="/free-lesson" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="action-content">
                        <h3>Бесплатный урок</h3>
                        <p>Попробуйте первый урок бесплатно</p>
                    </div>
                </a>
                <a href="/placement-test" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="action-content">
                        <h3>Тест уровня</h3>
                        <p>Определите ваш текущий уровень английского</p>
                    </div>
                </a>
            </div>

            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $user->total_lessons_completed }}</div>
                    <div class="stat-label">Пройдено уроков</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $user->current_streak }}</div>
                    <div class="stat-label">Дней подряд</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ round($user->total_study_time / 60) }}ч</div>
                    <div class="stat-label">Время обучения</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ strtoupper($user->level) }}</div>
                    <div class="stat-label">Текущий уровень</div>
                </div>
            </div>

            <div class="dashboard-tabs">
                <button class="profile-tab active" data-tab="courses">Мои курсы</button>
                <button class="profile-tab" data-tab="activity">Активность</button>
            </div>

            <div class="tab-content active" id="courses-tab">
                <h3 class="section-title">Активные курсы</h3>
                <div class="courses-grid">
                    @foreach($user->activeCourses as $userCourse)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h3>{{ $userCourse->course->title }}</h3>
                                <div class="course-level">{{ $userCourse->course->level }}</div>
                            </div>
                            <div class="course-content">
                                <p>{{ $userCourse->course->short_description }}</p>
                                <div class="course-progress">
                                    <div class="progress-header">
                                        <span class="progress-label">Прогресс</span>
                                        <span class="progress-value">{{ $userCourse->progress }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $userCourse->progress }}%"></div>
                                    </div>
                                </div>
                                <div class="course-stats">
                                    <span><i class="fas fa-play-circle"></i> {{ $userCourse->completed_lessons }}/{{ $userCourse->total_lessons }} уроков</span>
                                    <span><i class="fas fa-star"></i>
                                        @php
                                            $stats = $userCourse->stats ?? [];
                                            $avgScore = $stats['average_score'] ?? 4.8;
                                            echo number_format($avgScore / 20, 1);
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($user->activeCourses->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-book-open"></i>
                            <h3>У вас нет активных курсов</h3>
                            <p>Начните обучение, выбрав подходящий курс</p>
                            <a href="/courses" class="btn btn-primary">Выбрать курс</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="tab-content" id="activity-tab">
                <h3 class="section-title">Недавняя активность</h3>
                <div class="activity-timeline">
                    @foreach($user->userLessons as $userLesson)
                        @if($userLesson->completed)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Завершен урок</div>
                                    <div class="activity-description">Оценка: {{ $userLesson->score ?? 'N/A' }}% • Время: {{ round($userLesson->time_spent / 60) }} мин</div>
                                    <div class="activity-time">{{ $userLesson->completed_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if($user->userLessons->where('completed', true)->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <h3>Активность отсутствует</h3>
                            <p>Начните проходить уроки, чтобы увидеть здесь свою активность</p>
                        </div>
                    @endif
                </div>

                @if($user->placementTests->isNotEmpty())
                    <h3 class="section-title" style="margin-top: 2rem;">Последний тест уровня</h3>
                    <div class="test-result-card">
                        <div class="test-level">
                            <span class="level-badge">{{ $user->placementTests->first()->level }}</span>
                        </div>
                        <div class="test-score">
                            <span class="score">Результат: {{ $user->placementTests->first()->score }}%</span>
                        </div>
                        <div class="test-date">
                            <span>Пройден: {{ $user->placementTests->first()->completed_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Управление табами дашборда
        document.querySelectorAll('.dashboard-tabs .profile-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.dashboard-tabs .profile-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });

        // Анимация прогресс-баров
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.progress-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
@endsection
