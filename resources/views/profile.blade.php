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
                <!-- Блок для авторизованных пользователей -->
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
                    </div>
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
                            <div class="stat-number">{{ round($user->total_study_time / 60) }}ч</div>
                            <div class="stat-label">Время обучения</div>
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
                    <div class="achievements-grid">
                        @foreach($user->achievements as $achievement)
                            <div class="achievement-card">
                                <div class="achievement-icon">
                                    <i class="fas fa-{{ $achievement->icon }}"></i>
                                </div>
                                <h4>{{ $achievement->name }}</h4>
                                <p>{{ $achievement->description }}</p>
                                <div class="achievement-date">
                                    Получено: {{ \Carbon\Carbon::parse($achievement->pivot->achieved_at)->format('d.m.Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-content" id="activity-tab">
                    <h3 class="section-title">История активности</h3>
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
                    </div>
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
