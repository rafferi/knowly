@extends('app')
@section('title', 'Профиль - Knowly')
@section('content')
    @php
        function getCourseIcon($courseTitle) {
            $icons = [
                'Business' => 'briefcase',
                'Conversational' => 'comments',
                'IELTS' => 'pen-fancy',
                'IT' => 'laptop-code',
                'Travel' => 'plane',
                'Starter' => 'book'
            ];
            foreach ($icons as $key => $icon) {
                if (str_contains($courseTitle, $key)) {
                    return $icon;
                }
            }
            return 'graduation-cap';
        }
    @endphp

    <div class="profile-container">
        <div class="profile-card">
            @auth
                <div class="profile-header">
                    <div class="avatar-upload">
                        <div class="avatar-preview" onclick="document.getElementById('avatar-input').click()">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="Аватар пользователя">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                            <div class="avatar-edit">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <input type="file" id="avatar-input" class="avatar-upload-input" accept="image/*" onchange="handleAvatarUpload(this)">

                        @if($user->avatar_url)
                            <div class="avatar-actions">
                                <button type="button" class="avatar-btn avatar-btn-danger" onclick="deleteAvatar()">
                                    <i class="fas fa-trash"></i> Удалить
                                </button>
                            </div>
                        @endif
                    </div>
                    <h1>{{ $user->name }} <span class="level-badge">{{ $user->level_title }}</span></h1>
                    <p class="profile-subtitle">Ученик Knowly • {{ $user->xp }} XP</p>

                    <div class="xp-progress">
                        <div class="xp-info">
                            <span>Уровень {{ $user->level_title }}</span>
                            <span>{{ $user->xp }} XP</span>
                        </div>
                        <div class="xp-bar">
                            <div class="xp-fill" style="width: {{ $user->level_progress }}%"></div>
                        </div>
                        @php
                            $levels = [
                                'beginner' => 100,
                                'elementary' => 300,
                                'intermediate' => 600,
                                'upper_intermediate' => 1000,
                                'advanced' => 1500,
                                'master' => 'MAX'
                            ];
                            $nextLevelXp = $levels[$user->level] ?? 'MAX';
                        @endphp
                        @if($nextLevelXp !== 'MAX')
                            <div class="xp-next-level">
                                @php
                                    $levels = [
                                        'beginner' => 100,
                                        'elementary' => 300,
                                        'intermediate' => 600,
                                        'upper_intermediate' => 1000,
                                        'advanced' => 1500,
                                        'master' => 'MAX'
                                    ];
                                    $nextLevelXp = $levels[$user->level] ?? 'MAX';
                                @endphp
                                @if($nextLevelXp !== 'MAX')
                                    <small>До следующего уровня: {{ $nextLevelXp - $user->xp }} XP</small>
                                @else
                                    <small>Вы достигли максимального уровня!</small>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

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

                    <div class="profile-tabs">
                        <button class="profile-tab active" data-tab="overview">Обзор</button>
                        <button class="profile-tab" data-tab="learning">Обучение</button>
                        <button class="profile-tab" data-tab="achievements">Достижения</button>
                        <button class="profile-tab" data-tab="activity">Активность</button>
                        <button class="profile-tab" data-tab="settings">Настройки</button>
                    </div>

                    <div class="tab-content active" id="overview-tab">
                        @if($user->bio)
                            <div class="bio-section">
                                <h3 class="section-title">О себе</h3>
                                <div class="bio-text">{{ $user->bio }}</div>
                            </div>
                        @endif

                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                            @if($user->phone)
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $user->phone }}</span>
                                </div>
                            @endif
                            <div class="contact-item">
                                <i class="fas fa-calendar"></i>
                                <span>Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Уровень: {{ $user->level_title }}</span>
                            </div>
                        </div>

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
                                <div class="stat-number">{{ $user->activeCourses->count() }}</div>
                                <div class="stat-label">Активных курсов</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ strtoupper($user->level) }}</div>
                                <div class="stat-label">Текущий уровень</div>
                            </div>
                        </div>

                        <h3 class="section-title">Активные курсы</h3>
                        <div class="courses-grid">
                            @foreach($user->activeCourses as $userCourse)
                                <div class="course-card">
                                    <div class="course-header">
                                        <div class="course-icon">
                                            <i class="fas fa-{{ getCourseIcon($userCourse->course->title) }}"></i>
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

                                        <!-- Кнопки для курса -->
                                        <div class="course-actions mt-3">
                                            <div class="d-flex flex-column gap-2">
                                                @if($userCourse->progress >= 100)
                                                    <a href="{{ route('certificate.show', $userCourse->course->slug) }}"
                                                       class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-certificate"></i> Получить сертификат
                                                    </a>
                                                @endif
                                                <a href="{{ route('lectures.roadmap', ['courseSlug' => $userCourse->course->slug]) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-road"></i> Программа обучения
                                                </a>
                                                <a href="{{ route('lectures.next', ['courseSlug' => $userCourse->course->slug]) }}"
                                                   class="btn btn-outline btn-sm w-100">
                                                    <i class="fas fa-play-circle"></i> Продолжить
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-content" id="learning-tab">
                        <h3 class="section-title">Мои курсы</h3>
                        <div class="courses-grid">
                            @foreach($user->userCourses as $userCourse)
                                <div class="course-card">
                                    <div class="course-header">
                                        <div class="course-icon">
                                            <i class="fas fa-{{ getCourseIcon($userCourse->course->title) }}"></i>
                                        </div>
                                        <h3>{{ $userCourse->course->title }}</h3>
                                        <div class="course-level">{{ $userCourse->course->level }}</div>
                                        @if($userCourse->completed_at)
                                            <div class="course-badge completed">Завершен</div>
                                        @else
                                            <div class="course-badge in-progress">В процессе</div>
                                        @endif
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
                                            <span><i class="fas fa-clock"></i> {{ round(($userCourse->total_lessons * 45) / 60) }} часов</span>
                                        </div>

                                        <!-- Кнопки для курса -->
                                        <div class="course-actions mt-3">
                                            <div class="d-flex flex-column gap-2">
                                                @if($userCourse->progress >= 100)
                                                    <a href="{{ route('certificate.show', $userCourse->course->slug) }}"
                                                       class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-certificate"></i> Получить сертификат
                                                    </a>
                                                @endif
                                                <a href="{{ route('lectures.index', ['courseSlug' => $userCourse->course->slug]) }}"
                                                   class="btn btn-outline btn-sm w-100">
                                                    <i class="fas fa-book-open"></i> Лекции курса
                                                </a>
                                                <a href="{{ route('universal-tests.index') }}"
                                                   class="btn btn-outline btn-sm w-100">
                                                    <i class="fas fa-clipboard-check"></i> Тесты курса
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <h3 class="section-title">Статистика обучения</h3>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">{{ $user->userCourses->count() }}</div>
                                <div class="stat-label">Всего курсов</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $user->completedCourses->count() }}</div>
                                <div class="stat-label">Завершено курсов</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $user->total_lessons_completed }}</div>
                                <div class="stat-label">Пройдено уроков</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ round($user->total_study_time / 60) }}ч</div>
                                <div class="stat-label">Время обучения</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="achievements-tab">
                        <h3 class="section-title">Мои достижения</h3>
                        @if($achievementsData->count())
                            <div class="achievements-grid">
                                @foreach($achievementsData as $achievement)
                                    <div class="achievement-card">
                                        <div class="achievement-icon">
                                            <i class="fas fa-{{ $achievement->icon }}"></i>
                                        </div>
                                        <h4>{{ $achievement->name }}</h4>
                                        <p>{{ $achievement->description }}</p>
                                        <div class="achievement-date">
                                            Получено: {{ \Carbon\Carbon::parse($achievement->achieved_at)->format('d.m.Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-trophy" style="font-size: 3rem; color: #ccc;"></i>
                                <h4>Пока нет достижений</h4>
                                <p>Пройдите больше уроков и тестов, чтобы получить достижения</p>
                            </div>
                        @endif
                    </div>

                    <div class="tab-content" id="activity-tab">
                        <h3 class="section-title">История активности</h3>
                        @if($activities->count())
                            <div class="activity-timeline">
                                @foreach($activities as $activity)
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="{{ $activity['icon'] }}"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title">{{ $activity['title'] }}</div>
                                            <div class="activity-description">
                                                {{ $activity['description'] }}
                                                @if(isset($activity['score']))
                                                    • Оценка: {{ $activity['score'] }}%
                                                @endif
                                                @if(isset($activity['time_spent']))
                                                    • Время: {{ round($activity['time_spent'] / 60) }} мин
                                                @endif
                                            </div>
                                            <div class="activity-time">
                                                {{ \Carbon\Carbon::parse($activity['date'])->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-history" style="font-size: 3rem; color: #ccc;"></i>
                                <h4>Нет активности</h4>
                                <p>Начните обучение, чтобы увидеть здесь историю ваших действий</p>
                                <a href="{{ route('courses') }}" class="btn btn-primary">
                                    <i class="fas fa-play-circle"></i> Начать обучение
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="tab-content" id="settings-tab">
                        <div class="settings-section">
                            <h3 class="section-title">Личная информация</h3>
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name" class="form-label">Имя</label>
                                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="+7 (999) 999-99-99">
                                    @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="bio" class="form-label">О себе</label>
                                    <textarea id="bio" name="bio" class="form-input" rows="4" placeholder="Расскажите немного о себе...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="profile-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Сохранить изменения
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="settings-section">
                            <h3 class="section-title">Загрузка аватара</h3>
                            <div class="form-group">
                                <label class="form-label">Выберите изображение</label>
                                <div class="file-upload-area" onclick="document.getElementById('avatar-input-settings').click()">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Нажмите для загрузки фото</div>
                                    <div class="upload-hint">PNG, JPG, GIF до 5MB</div>
                                </div>
                                <input type="file" id="avatar-input-settings" class="avatar-upload-input" accept="image/*">
                            </div>
                        </div>

                        <div class="settings-section">
                            <h3 class="section-title">Безопасность</h3>
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="current_password" class="form-label">Текущий пароль</label>
                                    <input type="password" id="current_password" name="current_password" class="form-input" required>
                                    @error('current_password')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password" class="form-label">Новый пароль</label>
                                    <input type="password" id="new_password" name="new_password" class="form-input" required>
                                    @error('new_password')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation" class="form-label">Подтверждение нового пароля</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input" required>
                                </div>

                                <div class="profile-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Сменить пароль
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Кнопка выхода -->
                        <div class="settings-section">
                            <h3 class="section-title">Выход</h3>
                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit" class="btn-logout">
                                    <i class="fas fa-sign-out-alt"></i> Выйти из аккаунта
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                        <!-- Блок для неавторизованных пользователей -->
                        <div class="profile-header" style="text-align: center; padding: 3rem;">
                            <div class="avatar-preview" style="margin: 0 auto 2rem; cursor: default;">
                                <i class="fas fa-graduation-cap" style="font-size: 4rem; color: var(--grenadine);"></i>
                            </div>
                            <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Добро пожаловать в Knowly!</h1>
                            <p class="profile-subtitle" style="font-size: 1.3rem; margin-bottom: 0.5rem;">Платформа для изучения английского языка</p>
                            <p style="color: var(--ultimate-gray); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;">
                                Начните свой путь к свободному владению английским с бесплатного пробного урока
                            </p>

                            <!-- Кнопки функционала -->
                            <div class="profile-feature-buttons">
                                <a href="/free-lesson" class="btn-feature btn-feature-primary">
                                    <div class="btn-icon">
                                        <i class="fas fa-play-circle"></i>
                                    </div>
                                    <div class="btn-content">
                                        <h4>Бесплатный урок</h4>
                                        <p>Попробуйте первый урок бесплатно</p>
                                    </div>
                                </a>

                                <a href="/placement-test" class="btn-feature btn-feature-secondary">
                                    <div class="btn-icon">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <div class="btn-content">
                                        <h4>Тест на уровень</h4>
                                        <p>Определите свой уровень английского</p>
                                    </div>
                                </a>
                            </div>

                            <!-- Блок преимуществ -->
                            <div class="profile-benefits">
                                <h3>Почему стоит начать?</h3>
                                <div class="benefits-grid">
                                    <div class="benefit-item">
                                        <i class="fas fa-rocket"></i>
                                        <h4>Быстрый старт</h4>
                                        <p>Первый результат уже после пробного урока</p>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="fas fa-chart-line"></i>
                                        <h4>Персональный подход</h4>
                                        <p>Программа под ваш уровень и цели</p>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="fas fa-gamepad"></i>
                                        <h4>Интерактивное обучение</h4>
                                        <p>Увлекательные задания и практика</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
        </div>

        <style>
            .profile-container {
                max-width: 1200px;
                margin: 2rem auto;
                padding: 0 1rem;
            }

            .profile-card {
                background: white;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .profile-header {
                text-align: center;
                padding: 2rem;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            }

            .avatar-upload {
                margin-bottom: 1.5rem;
            }

            .avatar-preview {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                margin: 0 auto 1rem;
                background: white;
                border: 4px solid white;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                position: relative;
                cursor: pointer;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .avatar-preview img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .avatar-preview i {
                font-size: 4rem;
                color: var(--ultimate-gray);
            }

            .avatar-edit {
                position: absolute;
                bottom: 0;
                right: 0;
                width: 40px;
                height: 40px;
                background: var(--grenadine);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                cursor: pointer;
            }

            .avatar-actions {
                display: flex;
                justify-content: center;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }

            .avatar-btn {
                padding: 0.5rem 1rem;
                border: none;
                border-radius: 5px;
                font-size: 0.9rem;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .avatar-btn-danger {
                background: #dc3545;
                color: white;
            }

            .avatar-btn-danger:hover {
                background: #c82333;
            }

            .avatar-upload-input {
                display: none;
            }

            .profile-header h1 {
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
                color: var(--black-beauty);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .level-badge {
                background: var(--grenadine);
                color: white;
                padding: 0.3rem 1rem;
                border-radius: 20px;
                font-size: 1rem;
                font-weight: 600;
            }

            .profile-subtitle {
                color: var(--ultimate-gray);
                margin-bottom: 1.5rem;
                font-size: 1.1rem;
            }

            .xp-progress {
                max-width: 400px;
                margin: 0 auto;
            }

            .xp-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
                color: var(--ultimate-gray);
            }

            .xp-bar {
                height: 8px;
                background: var(--nimbus-cloud);
                border-radius: 4px;
                overflow: hidden;
            }

            .xp-fill {
                height: 100%;
                background: linear-gradient(90deg, var(--grenadine), #ff7a5a);
                border-radius: 4px;
                transition: width 0.5s ease;
            }

            .profile-quick-actions {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
                padding: 1.5rem;
                background: var(--gray-light);
            }

            .quick-action-card {
                background: white;
                border-radius: 15px;
                padding: 1.5rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                text-decoration: none;
                color: var(--black-beauty);
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .quick-action-card:hover {
                border-color: var(--grenadine);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .action-icon {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
            }

            .action-content h3 {
                margin-bottom: 0.25rem;
            }

            .action-content p {
                margin: 0;
                opacity: 0.9;
                font-size: 0.9rem;
            }

            .profile-tabs {
                display: flex;
                border-bottom: 2px solid var(--nimbus-cloud);
                padding: 0 1.5rem;
                overflow-x: auto;
            }

            .profile-tab {
                padding: 1rem 1.5rem;
                background: none;
                border: none;
                font-size: 1rem;
                font-weight: 600;
                color: var(--ultimate-gray);
                cursor: pointer;
                position: relative;
                white-space: nowrap;
            }

            .profile-tab:hover {
                color: var(--black-beauty);
            }

            .profile-tab.active {
                color: var(--grenadine);
            }

            .profile-tab.active::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 100%;
                height: 3px;
                background: var(--grenadine);
            }

            .tab-content {
                padding: 2rem;
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            .bio-section {
                margin-bottom: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                color: var(--black-beauty);
            }

            .bio-text {
                background: var(--gray-light);
                padding: 1.5rem;
                border-radius: 10px;
                line-height: 1.6;
                color: var(--black-beauty);
            }

            .contact-info {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .contact-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem;
                background: var(--gray-light);
                border-radius: 10px;
            }

            .contact-item i {
                color: var(--grenadine);
                width: 20px;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: white;
                border-radius: 10px;
                padding: 1.5rem;
                text-align: center;
                border: 2px solid var(--nimbus-cloud);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                border-color: var(--grenadine);
                transform: translateY(-2px);
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--grenadine);
                margin-bottom: 0.5rem;
            }

            .stat-label {
                color: var(--ultimate-gray);
                font-size: 0.9rem;
            }

            .courses-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 1.5rem;
            }

            .course-card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                border: 2px solid var(--nimbus-cloud);
                transition: all 0.3s ease;
            }

            .course-card:hover {
                border-color: var(--grenadine);
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            .course-header {
                padding: 1.5rem;
                background: var(--gray-light);
                border-bottom: 1px solid var(--nimbus-cloud);
                position: relative;
            }

            .course-icon {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .course-header h3 {
                font-size: 1.3rem;
                margin-bottom: 0.5rem;
                color: var(--black-beauty);
            }

            .course-level {
                display: inline-block;
                padding: 0.3rem 1rem;
                background: rgba(223, 63, 50, 0.1);
                color: var(--grenadine);
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .course-badge {
                position: absolute;
                top: 1rem;
                right: 1rem;
                padding: 0.3rem 0.8rem;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .course-badge.completed {
                background: #28a745;
                color: white;
            }

            .course-badge.in-progress {
                background: #ffc107;
                color: black;
            }

            .course-content {
                padding: 1.5rem;
            }

            .course-content p {
                color: var(--ultimate-gray);
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }

            .course-progress {
                margin-bottom: 1rem;
            }

            .progress-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
            }

            .progress-label {
                color: var(--ultimate-gray);
                font-size: 0.9rem;
            }

            .progress-value {
                color: var(--grenadine);
                font-weight: 600;
            }

            .progress-bar {
                height: 8px;
                background: var(--nimbus-cloud);
                border-radius: 4px;
                overflow: hidden;
            }

            .progress-fill {
                height: 100%;
                background: var(--grenadine);
                border-radius: 4px;
                transition: width 0.5s ease;
            }

            .course-stats {
                display: flex;
                justify-content: space-between;
                color: var(--ultimate-gray);
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .course-stats i {
                margin-right: 0.25rem;
                color: var(--grenadine);
            }

            /* Стили для кнопок курса */
            .course-actions {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid var(--nimbus-cloud);
            }

            .course-actions .btn-sm {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .course-actions .btn-outline {
                border-color: var(--grenadine);
                color: var(--grenadine);
                transition: all 0.3s ease;
            }

            .course-actions .btn-outline:hover {
                background-color: var(--grenadine);
                color: white;
            }

            .course-actions .btn-success {
                background-color: #28a745;
                color: white;
                border: none;
            }

            .course-actions .btn-success:hover {
                background-color: #218838;
            }

            .achievements-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .achievement-card {
                background: white;
                border-radius: 15px;
                padding: 1.5rem;
                text-align: center;
                border: 2px solid var(--nimbus-cloud);
                transition: all 0.3s ease;
            }

            .achievement-card:hover {
                border-color: var(--grenadine);
                transform: translateY(-2px);
            }

            .achievement-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                color: white;
                font-size: 2rem;
            }

            .achievement-card h4 {
                margin-bottom: 0.5rem;
                color: var(--black-beauty);
            }

            .achievement-card p {
                color: var(--ultimate-gray);
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .achievement-date {
                color: var(--grenadine);
                font-size: 0.8rem;
                font-weight: 600;
            }

            .activity-timeline {
                position: relative;
                padding-left: 2rem;
            }

            .activity-timeline::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 2px;
                background: var(--grenadine);
            }

            .activity-item {
                position: relative;
                margin-bottom: 2rem;
                padding-left: 2rem;
            }

            .activity-item:last-child {
                margin-bottom: 0;
            }

            .activity-icon {
                position: absolute;
                left: -1.5rem;
                top: 0;
                width: 3rem;
                height: 3rem;
                background: white;
                border: 2px solid var(--grenadine);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--grenadine);
                font-size: 1rem;
            }

            .activity-content {
                background: var(--gray-light);
                padding: 1rem;
                border-radius: 10px;
            }

            .activity-title {
                font-weight: 600;
                margin-bottom: 0.25rem;
                color: var(--black-beauty);
            }

            .activity-description {
                color: var(--ultimate-gray);
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .activity-time {
                color: var(--grenadine);
                font-size: 0.8rem;
            }

            .settings-section {
                margin-bottom: 2rem;
                padding-bottom: 2rem;
                border-bottom: 1px solid var(--nimbus-cloud);
            }

            .settings-section:last-child {
                border-bottom: none;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: var(--black-beauty);
            }

            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 2px solid var(--nimbus-cloud);
                border-radius: 8px;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .form-input:focus {
                border-color: var(--grenadine);
                outline: none;
                box-shadow: 0 0 0 3px rgba(223, 63, 50, 0.1);
            }

            .error-message {
                color: #dc3545;
                font-size: 0.9rem;
                margin-top: 0.25rem;
            }

            .file-upload-area {
                border: 2px dashed var(--nimbus-cloud);
                border-radius: 10px;
                padding: 3rem 2rem;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .file-upload-area:hover,
            .file-upload-area.dragover {
                border-color: var(--grenadine);
                background: rgba(223, 63, 50, 0.05);
            }

            .upload-icon {
                font-size: 2.5rem;
                color: var(--grenadine);
                margin-bottom: 1rem;
            }

            .upload-text {
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: var(--black-beauty);
            }

            .upload-hint {
                color: var(--ultimate-gray);
                font-size: 0.9rem;
            }

            .profile-actions {
                margin-top: 1rem;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .btn-primary {
                background: var(--grenadine);
                color: white;
            }

            .btn-primary:hover {
                background: #df3f32;
                transform: translateY(-2px);
            }

            .btn-outline {
                background: white;
                border: 2px solid var(--grenadine);
                color: var(--grenadine);
            }

            .btn-outline:hover {
                background: var(--grenadine);
                color: white;
                transform: translateY(-2px);
            }

            .btn-logout {
                width: 100%;
                padding: 1rem;
                background: #dc3545;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                transition: all 0.3s ease;
            }

            .btn-logout:hover {
                background: #c82333;
                transform: translateY(-2px);
            }

            .profile-feature-buttons {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                margin-bottom: 3rem;
            }

            .btn-feature {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                padding: 1.5rem;
                border-radius: 15px;
                text-decoration: none;
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .btn-feature-primary {
                background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
                color: white;
            }

            .btn-feature-secondary {
                background: white;
                border-color: var(--grenadine);
                color: var(--black-beauty);
            }

            .btn-feature:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            }

            .btn-icon {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
            }

            .btn-feature-secondary .btn-icon {
                background: rgba(223, 63, 50, 0.1);
                color: var(--grenadine);
            }

            .btn-content h4 {
                margin-bottom: 0.25rem;
                font-size: 1.2rem;
            }

            .btn-content p {
                margin: 0;
                opacity: 0.9;
                font-size: 0.9rem;
            }

            .btn-feature-secondary .btn-content p {
                color: var(--ultimate-gray);
            }

            .profile-benefits {
                margin-top: 3rem;
            }

            .profile-benefits h3 {
                text-align: center;
                margin-bottom: 2rem;
                font-size: 1.8rem;
                color: var(--black-beauty);
            }

            .benefits-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .benefit-item {
                text-align: center;
                padding: 1.5rem;
                background: var(--gray-light);
                border-radius: 15px;
            }

            .benefit-item i {
                font-size: 2.5rem;
                color: var(--grenadine);
                margin-bottom: 1rem;
            }

            .benefit-item h4 {
                margin-bottom: 0.5rem;
                color: var(--black-beauty);
            }

            .benefit-item p {
                color: var(--ultimate-gray);
                margin: 0;
            }

            @media (max-width: 768px) {
                .profile-header h1 {
                    font-size: 2rem;
                    flex-direction: column;
                }

                .profile-tabs {
                    flex-wrap: wrap;
                }

                .courses-grid {
                    grid-template-columns: 1fr;
                }

                .benefits-grid {
                    grid-template-columns: 1fr;
                }

                .btn-feature {
                    flex-direction: column;
                    text-align: center;
                }
            }
            .xp-next-level {
                margin-top: 0.5rem;
                text-align: center;
                font-size: 0.8rem;
                color: var(--ultimate-gray);
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: white;
                border-radius: 10px;
                padding: 1.5rem;
                text-align: center;
                border: 2px solid var(--nimbus-cloud);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                border-color: var(--grenadine);
                transform: translateY(-2px);
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--grenadine);
                margin-bottom: 0.5rem;
            }

            .stat-label {
                color: var(--ultimate-gray);
                font-size: 0.9rem;
            }
        </style>
        @endsection

        @section('scripts')
            <script>
                // Управление табами профиля
                document.querySelectorAll('.profile-tab').forEach(tab => {
                    tab.addEventListener('click', function() {
                        document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                        this.classList.add('active');
                        const tabId = this.getAttribute('data-tab');
                        document.getElementById(tabId + '-tab').classList.add('active');
                    });
                });

                // Загрузка аватара
                function handleAvatarUpload(input) {
                    if (input.files && input.files[0]) {
                        const formData = new FormData();
                        formData.append('avatar', input.files[0]);
                        formData.append('_token', '{{ csrf_token() }}');

                        const avatarPreview = document.querySelector('.avatar-preview');
                        avatarPreview.style.opacity = '0.5';

                        fetch('{{ route("profile.avatar.update") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                avatarPreview.style.opacity = '1';
                                if (data.success) {
                                    if (data.avatar_url) {
                                        const avatarImg = avatarPreview.querySelector('img');
                                        if (avatarImg) {
                                            avatarImg.src = data.avatar_url;
                                        } else {
                                            avatarPreview.innerHTML = `<img src="${data.avatar_url}" alt="Аватар пользователя">`;
                                            avatarPreview.innerHTML += '<div class="avatar-edit"><i class="fas fa-camera"></i></div>';
                                        }
                                    }

                                    if (!document.querySelector('.avatar-actions')) {
                                        const avatarActions = document.createElement('div');
                                        avatarActions.className = 'avatar-actions';
                                        avatarActions.innerHTML = `
                                    <button type="button" class="avatar-btn avatar-btn-danger" onclick="deleteAvatar()">
                                        <i class="fas fa-trash"></i> Удалить
                                    </button>
                                `;
                                        document.querySelector('.avatar-upload').appendChild(avatarActions);
                                    }

                                    showNotification(data.message, 'success');
                                    updateNavAvatar(data.avatar_url);
                                } else {
                                    let errorMessage = data.message || 'Ошибка при загрузке аватара';
                                    if (data.errors) {
                                        errorMessage += ': ' + Object.values(data.errors).join(', ');
                                    }
                                    showNotification(errorMessage, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                avatarPreview.style.opacity = '1';
                                showNotification('Ошибка при загрузке аватара. Проверьте подключение.', 'error');
                            });
                    }
                }

                // Удаление аватара
                function deleteAvatar() {
                    if (confirm('Вы уверены, что хотите удалить аватар?')) {
                        fetch('{{ route("profile.avatar.delete") }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    const avatarPreview = document.querySelector('.avatar-preview');
                                    avatarPreview.innerHTML = '<i class="fas fa-user"></i><div class="avatar-edit"><i class="fas fa-camera"></i></div>';

                                    const avatarActions = document.querySelector('.avatar-actions');
                                    if (avatarActions) {
                                        avatarActions.remove();
                                    }

                                    showNotification(data.message, 'success');
                                    updateNavAvatar(null);
                                } else {
                                    showNotification(data.message || 'Ошибка при удалении аватара', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('Ошибка при удалении аватара. Проверьте подключение.', 'error');
                            });
                    }
                }

                // Обновление аватара в навигации
                function updateNavAvatar(avatarUrl) {
                    const navAvatar = document.querySelector('.nav-avatar');
                    const navAvatarIcon = document.querySelector('.nav-avatar-icon');

                    if (avatarUrl) {
                        if (navAvatar) {
                            navAvatar.src = avatarUrl;
                        } else if (navAvatarIcon) {
                            const newAvatar = document.createElement('img');
                            newAvatar.src = avatarUrl;
                            newAvatar.alt = 'Аватар';
                            newAvatar.className = 'nav-avatar';
                            newAvatar.style.width = '32px';
                            newAvatar.style.height = '32px';
                            newAvatar.style.borderRadius = '50%';
                            newAvatar.style.marginRight = '0.5rem';
                            newAvatar.style.objectFit = 'cover';

                            navAvatarIcon.parentNode.replaceChild(newAvatar, navAvatarIcon);
                        }
                    } else {
                        const navAvatar = document.querySelector('.nav-avatar');
                        if (navAvatar && navAvatar.tagName === 'IMG') {
                            const newIcon = document.createElement('i');
                            newIcon.className = 'fas fa-user nav-avatar-icon';
                            newIcon.style.marginRight = '0.5rem';

                            navAvatar.parentNode.replaceChild(newIcon, navAvatar);
                        }
                    }
                }

                // Drag and drop для загрузки аватара
                const uploadArea = document.querySelector('.file-upload-area');
                if (uploadArea) {
                    uploadArea.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('dragover');
                    });

                    uploadArea.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                    });

                    uploadArea.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            document.getElementById('avatar-input-settings').files = files;
                            handleAvatarUpload(document.getElementById('avatar-input-settings'));
                        }
                    });
                }

                // Уведомления
                function showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `alert alert-${type}`;
                    notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                z-index: 10000;
                max-width: 300px;
                animation: slideInRight 0.3s ease;
            `;
                    notification.innerHTML = `<p>${message}</p>`;

                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.style.animation = 'slideOutRight 0.3s ease';
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    }, 5000);
                }

                // Обработчик загрузки аватара из настроек
                document.getElementById('avatar-input-settings')?.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        handleAvatarUpload(this);
                    }
                });

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

                // Стили для анимаций уведомлений
                const style = document.createElement('style');
                style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
                document.head.appendChild(style);
            </script>
@endsection
