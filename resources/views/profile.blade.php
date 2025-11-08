@extends('app')

@section('title', 'Профиль - Knowly')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="avatar-upload">
                    <div class="avatar-preview" onclick="document.getElementById('avatar-input').click()">
                        @if($user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="Аватар пользователя">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                        <div class="avatar-edit">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <input type="file" id="avatar-input" class="avatar-upload-input" accept="image/*" onchange="handleAvatarUpload(this)">

                    @if($user->avatar)
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

            <div class="profile-tabs">
                <button class="profile-tab active" data-tab="overview">Обзор</button>
                <button class="profile-tab" data-tab="learning">Обучение</button>
                <button class="profile-tab" data-tab="achievements">Достижения</button>
                <button class="profile-tab" data-tab="activity">Активность</button>
                <button class="profile-tab" data-tab="settings">Настройки</button>
            </div>

            <!-- Вкладка обзора -->
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
                        <div class="stat-number">24</div>
                        <div class="stat-label">Пройдено уроков</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">16</div>
                        <div class="stat-label">Дней подряд</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">38ч</div>
                        <div class="stat-label">Время обучения</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">B2</div>
                        <div class="stat-label">Текущий уровень</div>
                    </div>
                </div>

                <div class="progress-section">
                    <h3 class="section-title">Прогресс обучения</h3>
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">Грамматика</span>
                            <span class="progress-value">78%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 78%"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">Словарный запас</span>
                            <span class="progress-value">65%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">Разговорная практика</span>
                            <span class="progress-value">82%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 82%"></div>
                        </div>
                    </div>
                </div>

                <h3 class="section-title">Активные курсы</h3>
                <div class="courses-grid">
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3>Business English</h3>
                            <div class="course-level">B1-B2</div>
                        </div>
                        <div class="course-content">
                            <p>Деловой английский для работы</p>
                            <div class="course-progress">
                                <div class="progress-header">
                                    <span class="progress-label">Прогресс</span>
                                    <span class="progress-value">65%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 65%"></div>
                                </div>
                            </div>
                            <div class="course-stats">
                                <span><i class="fas fa-play-circle"></i> 12/18 уроков</span>
                                <span><i class="fas fa-star"></i> 4.8</span>
                            </div>
                        </div>
                    </div>

                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3>Разговорный английский</h3>
                            <div class="course-level">A2-B1</div>
                        </div>
                        <div class="course-content">
                            <p>Повседневное общение</p>
                            <div class="course-progress">
                                <div class="progress-header">
                                    <span class="progress-label">Прогресс</span>
                                    <span class="progress-value">42%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 42%"></div>
                                </div>
                            </div>
                            <div class="course-stats">
                                <span><i class="fas fa-play-circle"></i> 8/20 уроков</span>
                                <span><i class="fas fa-star"></i> 4.9</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Вкладка обучения -->
            <div class="tab-content" id="learning-tab">
                <h3 class="section-title">Мои курсы</h3>
                <div class="courses-grid">
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3>Business English</h3>
                            <div class="course-level">B1-B2</div>
                        </div>
                        <div class="course-content">
                            <p>Деловой английский для работы и карьеры</p>
                            <div class="course-progress">
                                <div class="progress-header">
                                    <span class="progress-label">Прогресс</span>
                                    <span class="progress-value">65%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 65%"></div>
                                </div>
                            </div>
                            <div class="course-stats">
                                <span><i class="fas fa-play-circle"></i> 12/18 уроков</span>
                                <span><i class="fas fa-clock"></i> 15 часов</span>
                            </div>
                        </div>
                    </div>

                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3>Разговорный английский</h3>
                            <div class="course-level">A2-B1</div>
                        </div>
                        <div class="course-content">
                            <p>Повседневное общение и диалоги</p>
                            <div class="course-progress">
                                <div class="progress-header">
                                    <span class="progress-label">Прогресс</span>
                                    <span class="progress-value">42%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 42%"></div>
                                </div>
                            </div>
                            <div class="course-stats">
                                <span><i class="fas fa-play-circle"></i> 8/20 уроков</span>
                                <span><i class="fas fa-clock"></i> 10 часов</span>
                            </div>
                        </div>
                    </div>

                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon">
                                <i class="fas fa-pen-fancy"></i>
                            </div>
                            <h3>Подготовка к IELTS</h3>
                            <div class="course-level">B2-C1</div>
                        </div>
                        <div class="course-content">
                            <p>Полная подготовка к экзамену</p>
                            <div class="course-progress">
                                <div class="progress-header">
                                    <span class="progress-label">Прогресс</span>
                                    <span class="progress-value">25%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 25%"></div>
                                </div>
                            </div>
                            <div class="course-stats">
                                <span><i class="fas fa-play-circle"></i> 5/24 уроков</span>
                                <span><i class="fas fa-clock"></i> 8 часов</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="section-title">Статистика обучения</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">94%</div>
                        <div class="stat-label">Посещаемость</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">4.7</div>
                        <div class="stat-label">Средняя оценка</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Пройдено тестов</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">86%</div>
                        <div class="stat-label">Успеваемость</div>
                    </div>
                </div>
            </div>

            <!-- Вкладка достижений -->
            <div class="tab-content" id="achievements-tab">
                <h3 class="section-title">Мои достижения</h3>
                <div class="achievements-grid">
                    <div class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <h4>Неделя усердия</h4>
                        <p>7 дней подряд обучения</p>
                    </div>
                    <div class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4>Скоростник</h4>
                        <p>10 уроков за 2 дня</p>
                    </div>
                    <div class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h4>Грамматический гений</h4>
                        <p>100% по грамматике</p>
                    </div>
                    <div class="achievement-card">
                        <div class="achievement-icon achievement-locked">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h4>Мастер слова</h4>
                        <p>1000 слов изучено</p>
                    </div>
                    <div class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Первый шаг</h4>
                        <p>Первый урок завершен</p>
                    </div>
                    <div class="achievement-card">
                        <div class="achievement-icon achievement-locked">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4>Полиглот</h4>
                        <p>5 курсов завершено</p>
                    </div>
                </div>
            </div>

            <!-- Вкладка активности -->
            <div class="tab-content" id="activity-tab">
                <h3 class="section-title">История активности</h3>
                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Завершен урок: Business Meeting</div>
                            <div class="activity-description">Курс: Business English • Оценка: 95%</div>
                            <div class="activity-time">Сегодня, 14:30</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Изучены новые слова</div>
                            <div class="activity-description">15 новых слов по теме "Офис"</div>
                            <div class="activity-time">Вчера, 19:15</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Пройден тест: Grammar Intermediate</div>
                            <div class="activity-description">Результат: 88% • Уровень: B1</div>
                            <div class="activity-time">2 дня назад, 10:00</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Получено достижение</div>
                            <div class="activity-description">"Неделя усердия" - 7 дней подряд</div>
                            <div class="activity-time">3 дня назад, 09:30</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Вкладка настроек -->
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

                <div class="settings-section">
                    <h3 class="section-title">Настройки уведомлений</h3>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Email уведомления</h4>
                            <p>Получать уведомления на почту</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Напоминания о уроках</h4>
                            <p>Уведомления о предстоящих уроках</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Новости платформы</h4>
                            <p>Обновления и новые функции</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Переключение вкладок
        document.querySelectorAll('.profile-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });

        // Обработка загрузки аватара
        function handleAvatarUpload(input) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('avatar', input.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                // Показываем индикатор загрузки
                const avatarPreview = document.querySelector('.avatar-preview');
                avatarPreview.style.opacity = '0.5';

                // Используем правильный маршрут для загрузки только аватара
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
                                // Обновляем изображение аватара
                                const avatarImg = avatarPreview.querySelector('img');
                                if (avatarImg) {
                                    avatarImg.src = data.avatar_url;
                                } else {
                                    // Создаем изображение если его нет
                                    avatarPreview.innerHTML = `<img src="${data.avatar_url}" alt="Аватар пользователя">`;
                                    avatarPreview.innerHTML += '<div class="avatar-edit"><i class="fas fa-camera"></i></div>';
                                }
                            }
                            // Показываем кнопку удаления
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
                            // Показываем уведомление
                            showNotification(data.message, 'success');

                            // Обновляем аватар в навигации
                            updateNavAvatar(data.avatar_url);
                        } else {
                            // Безопасная обработка ошибок
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
                            // Восстанавливаем иконку по умолчанию
                            const avatarPreview = document.querySelector('.avatar-preview');
                            avatarPreview.innerHTML = '<i class="fas fa-user"></i><div class="avatar-edit"><i class="fas fa-camera"></i></div>';

                            // Убираем кнопку удаления
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

        // Функция обновления аватара в навигации
        function updateNavAvatar(avatarUrl) {
            const navAvatar = document.querySelector('.nav-avatar');
            const navAvatarIcon = document.querySelector('.nav-avatar-icon');

            if (avatarUrl) {
                if (navAvatar) {
                    navAvatar.src = avatarUrl;
                } else if (navAvatarIcon) {
                    // Заменяем иконку на изображение
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
                // Если аватар удален, возвращаем иконку
                const navAvatar = document.querySelector('.nav-avatar');
                if (navAvatar && navAvatar.tagName === 'IMG') {
                    const newIcon = document.createElement('i');
                    newIcon.className = 'fas fa-user nav-avatar-icon';
                    newIcon.style.marginRight = '0.5rem';

                    navAvatar.parentNode.replaceChild(newIcon, navAvatar);
                }
            }
        }

        // Drag and drop для загрузки файлов
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

        // Функция показа уведомлений
        function showNotification(message, type) {
            // Создаем элемент уведомления
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

            // Удаляем уведомление через 5 секунд
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Автоматическая загрузка аватара при выборе файла в настройках
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

        // Добавляем CSS анимации для уведомлений
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
