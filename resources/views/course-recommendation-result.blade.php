@extends('app')

@section('title', 'Рекомендация курса - Knowly')

@section('content')
    <div class="results-hero">
        <div class="profile-card results-card">
            <div class="results-header">
                <div class="results-trophy">
                    <i class="fas fa-award"></i>
                </div>
                <div class="results-title">Ваша персональная рекомендация</div>
                <p class="results-subtitle">Мы подобрали для вас идеальное направление обучения</p>
            </div>

            <div class="results-main">
                @if($recommendation)
                    <div class="level-badge-large">
                        {{ $recommendation['course_name'] }}
                    </div>

                    <div class="results-stats">
                        <div class="result-stat">
                            <div class="stat-icon"><i class="fas fa-bullseye"></i></div>
                            <div class="stat-value">{{ $recommendation['intensity'] }}</div>
                            <div class="stat-label">Интенсивность</div>
                        </div>

                        <div class="result-stat">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-value">{{ $recommendation['duration'] }}</div>
                            <div class="stat-label">Продолжительность</div>
                        </div>

                        <div class="result-stat">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-value">{{ $recommendation['format'] }}</div>
                            <div class="stat-label">Формат</div>
                        </div>
                    </div>

                    <div style="text-align: left; margin: 2rem 0;">
                        <h3 style="color: var(--black-beauty); margin-bottom: 1rem;">Почему этот курс вам подходит:</h3>
                        <p style="color: var(--ultimate-gray); line-height: 1.6; font-size: 1.1rem;">
                            {{ $recommendation['description'] }}
                        </p>
                    </div>

                    <div style="background: var(--gray-light); padding: 2rem; border-radius: 15px; margin: 2rem 0; border-left: 4px solid var(--grenadine);">
                        <h4 style="color: var(--black-beauty); margin-bottom: 1rem;"><i class="fas fa-check-circle" style="color: var(--grenadine);"></i> Что вы получите:</h4>
                        <ul style="color: var(--ultimate-gray); line-height: 1.6; padding-left: 1.5rem;">
                            @foreach($recommendation['benefits'] as $benefit)
                                <li style="margin-bottom: 0.5rem;">{{ $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem 2rem;">
                        <div class="completion-icon" style="color: var(--ultimate-gray);">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h2 style="color: var(--black-beauty); margin-bottom: 1rem;">Не удалось определить рекомендацию</h2>
                        <p style="color: var(--ultimate-gray); margin-bottom: 2rem;">Попробуйте пройти тест еще раз</p>
                        <a href="/course-recommendation-test" class="btn btn-primary">Пройти тест снова</a>
                    </div>
                @endif
            </div>

            @if($recommendation)
                <div class="recommended-courses">
                    <div class="card-header">
                        <h2><i class="fas fa-rocket"></i> Начните обучение прямо сейчас</h2>
                    </div>

                    <div class="courses-grid-improved">
                        <div class="course-card-improved">
                            <div class="course-header-improved">
                                <div class="course-icon-improved">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 style="color: white; margin: 0; font-size: 1.4rem;">{{ $recommendation['course_name'] }}</h3>
                                <div class="course-level-improved">Рекомендовано</div>
                            </div>
                            <div class="course-content-improved">
                                <h4>Идеально подходит для ваших целей</h4>
                                <p>Этот курс специально разработан для достижения именно тех результатов, которые вы указали в тесте.</p>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1.5rem 0;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 1.2rem; font-weight: 600; color: var(--grenadine);">{{ $recommendation['lessons_count'] }}</div>
                                        <div style="font-size: 0.8rem; color: var(--ultimate-gray);">уроков</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 1.2rem; font-weight: 600; color: var(--grenadine);">{{ $recommendation['price_range'] }}</div>
                                        <div style="font-size: 0.8rem; color: var(--ultimate-gray);">стоимость</div>
                                    </div>
                                </div>

                                <a href="/signup" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center;">
                                    <i class="fas fa-play-circle"></i> Начать курс
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="results-actions">
                    <a href="/courses" class="btn btn-secondary">
                        <i class="fas fa-book"></i> Посмотреть все курсы
                    </a>
                    <a href="/signup" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Зарегистрироваться
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
