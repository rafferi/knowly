@extends('app')

@section('title', 'Личный кабинет - Knowly')

@section('content')
    <div class="dashboard-hero">
        <div class="dashboard-background">
            <div class="dashboard-shape shape-1"></div>
            <div class="dashboard-shape shape-2"></div>
            <div class="dashboard-shape shape-3"></div>
        </div>

        <div class="container">
            <!-- Главная карточка приветствия -->
            <div class="dashboard-main-card">
                <div class="welcome-section">
                    <div class="welcome-content">
                        <div class="user-badge">
                            <div class="user-avatar-large">
                                @if(Auth::user()->avatar_url)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="Аватар">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div class="user-info">
                                <h1>Добро пожаловать, <span class="user-name">{{ Auth::user()->name }}</span>!</h1>
                                <p class="welcome-subtitle">Рады видеть вас снова в вашем личном кабинете</p>
                                <div class="user-level">
                                    <span class="level-badge">{{ Auth::user()->level_title }}</span>
                                    <span class="xp-count">{{ Auth::user()->xp }} XP</span>
                                </div>
                            </div>
                        </div>

                        <div class="motivation-quote">
                            <i class="fas fa-quote-left"></i>
                            <p>Каждый день изучения английского - это шаг к новым возможностям!</p>
                        </div>
                    </div>

                    <div class="stats-overview">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">24</div>
                                <div class="stat-label">Пройдено уроков</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">16</div>
                                <div class="stat-label">Дней подряд</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">38ч</div>
                                <div class="stat-label">Время обучения</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Сетка с карточками -->
            <div class="dashboard-grid">
                <!-- Активные курсы -->
                <div class="dashboard-card wide">
                    <div class="card-header">
                        <h2><i class="fas fa-book-open"></i> Активные курсы</h2>
                        <a href="#" class="see-all">Все курсы <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="courses-list">
                        <div class="course-progress-item">
                            <div class="course-info">
                                <div class="course-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="course-details">
                                    <h3>Business English</h3>
                                    <p>Деловой английский для работы</p>
                                    <div class="course-meta">
                                        <span class="level-tag">B1-B2</span>
                                        <span class="lessons-count">12/18 уроков</span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-section">
                                <div class="progress-header">
                                    <span>Прогресс</span>
                                    <span class="progress-percent">65%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="course-progress-item">
                            <div class="course-info">
                                <div class="course-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="course-details">
                                    <h3>Разговорный английский</h3>
                                    <p>Повседневное общение</p>
                                    <div class="course-meta">
                                        <span class="level-tag">A2-B1</span>
                                        <span class="lessons-count">8/20 уроков</span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-section">
                                <div class="progress-header">
                                    <span>Прогресс</span>
                                    <span class="progress-percent">42%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 42%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ближайшие уроки -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-calendar-alt"></i> Ближайшие уроки</h2>
                    </div>
                    <div class="lessons-list">
                        <div class="lesson-item upcoming">
                            <div class="lesson-time">
                                <div class="lesson-date">Сегодня</div>
                                <div class="lesson-hour">15:30</div>
                            </div>
                            <div class="lesson-info">
                                <h4>Business Meeting</h4>
                                <p>Курс: Business English</p>
                                <span class="teacher">С преподавателем Олег</span>
                            </div>
                            <button class="join-btn">
                                <i class="fas fa-video"></i>
                            </button>
                        </div>

                        <div class="lesson-item">
                            <div class="lesson-time">
                                <div class="lesson-date">Завтра</div>
                                <div class="lesson-hour">11:00</div>
                            </div>
                            <div class="lesson-info">
                                <h4>Everyday Conversations</h4>
                                <p>Курс: Разговорный английский</p>
                                <span class="teacher">С преподавателем Дмитрий</span>
                            </div>
                            <button class="join-btn disabled">
                                <i class="fas fa-clock"></i>
                            </button>
                        </div>
                    </div>
                </div>


                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-trophy"></i> Достижения</h2>
                    </div>
                    <div class="achievements-preview">
                        <div class="achievement-item unlocked">
                            <div class="achievement-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="achievement-info">
                                <h4>Неделя усердия</h4>
                                <p>7 дней подряд обучения</p>
                            </div>
                        </div>

                        <div class="achievement-item unlocked">
                            <div class="achievement-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="achievement-info">
                                <h4>Скоростник</h4>
                                <p>10 уроков за 2 дня</p>
                            </div>
                        </div>

                        <div class="achievement-item locked">
                            <div class="achievement-icon">
                                <i class="fas fa-gem"></i>
                            </div>
                            <div class="achievement-info">
                                <h4>Грамматический гений</h4>
                                <p>100% по грамматике</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="/profile#achievements" class="view-more">Все достижения <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Быстрые действия -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2><i class="fas fa-rocket"></i> Быстрые действия</h2>
                    </div>
                    <div class="quick-actions">
                        <a href="#" class="quick-action primary">
                            <div class="action-icon">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="action-text">
                                <h4>Продолжить обучение</h4>
                                <p>Следующий урок</p>
                            </div>
                        </a>

                        <a href="/profile" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="action-text">
                                <h4>Настройки профиля</h4>
                                <p>Изменить данные</p>
                            </div>
                        </a>

                        <a href="#" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="action-text">
                                <h4>Материалы</h4>
                                <p>Скачать файлы</p>
                            </div>
                        </a>

                        <a href="#" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="action-text">
                                <h4>Поддержка</h4>
                                <p>Помощь и вопросы</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Прогресс обучения -->
                <div class="dashboard-card wide">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-line"></i> Прогресс обучения</h2>
                    </div>
                    <div class="progress-stats">
                        <div class="progress-category">
                            <div class="progress-info">
                                <span class="category-name">Грамматика</span>
                                <span class="category-percent">78%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 78%"></div>
                            </div>
                        </div>

                        <div class="progress-category">
                            <div class="progress-info">
                                <span class="category-name">Словарный запас</span>
                                <span class="category-percent">65%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="progress-category">
                            <div class="progress-info">
                                <span class="category-name">Разговорная практика</span>
                                <span class="category-percent">82%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 82%"></div>
                            </div>
                        </div>

                        <div class="progress-category">
                            <div class="progress-info">
                                <span class="category-name">Аудирование</span>
                                <span class="category-percent">71%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 71%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Анимация прогресс-баров при загрузке
        window.addEventListener('load', function() {
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
