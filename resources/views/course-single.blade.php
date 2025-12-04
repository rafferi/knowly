@extends('app')

@section('title', $course->title . ' - Knowly')

@section('content')
    <section class="courses-hero-new" style="margin-top: 80px;">
        <div class="courses-hero-container-new">
            <div class="courses-hero-content-new">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses') }}">Курсы</a></li>
                        <li class="breadcrumb-item active">{{ $course->title }}</li>
                    </ol>
                </nav>

                <h1 class="courses-hero-title-new mb-3">{{ $course->title }}</h1>
                <p class="courses-hero-description-new mb-4">{{ $course->short_description }}</p>

                <div class="mb-4">
                    <span class="badge bg-grenadine me-2">Уровень: {{ $course->level }}</span>
                    <span class="badge bg-success me-2">{{ $course->duration }} месяцев</span>
                    <span class="badge bg-info">{{ $course->group_size }} в группе</span>
                </div>

                <div class="courses-hero-buttons-new course-actions mt-4">
                    <a href="/signup" class="btn-primary-new me-3">
                        <i class="fas fa-play-circle"></i> Бесплатный пробный урок
                    </a>
                    <a href="{{ route('learning.plan', $course->slug) }}" class="btn-secondary-new">
                        <i class="fas fa-graduation-cap"></i> Учебный план
                    </a>
                </div>
            </div>

            <div class="courses-hero-visual-new">
                <div class="dashboard-card" style="max-width: 400px; margin: 0 auto;">
                    <div class="card-header bg-grenadine text-white">
                        <h5 class="mb-0">Стоимость обучения</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="price-display-new mb-3">
                            <span class="price-main-new">{{ number_format($course->price, 0, '', ' ') }} ₽</span>
                            <span class="price-period-new">/месяц</span>
                        </div>
                        <div class="price-note-new">При оплате за весь курс - скидка 20%</div>

                        <ul class="pricing-features mt-4">
                            @foreach(json_decode($course->features) as $feature)
                                <li><i class="fas fa-check text-success me-2"></i> {{ $feature }}</li>
                            @endforeach
                        </ul>

                        <div class="course-stats-new row text-center mt-4 mb-4">
                            <div class="col-4">
                                <div class="stat-number-new">{{ $course->duration }}</div>
                                <div class="stat-label-new">месяцев</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-number-new">{{ $course->lessons_count }}</div>
                                <div class="stat-label-new">уроков</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-number-new">{{ $course->group_size }}</div>
                                <div class="stat-label-new">в группе</div>
                            </div>
                        </div>

                        <a href="/signup" class="btn-primary-new w-100">
                            Начать обучение
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features" style="background: var(--gray-light);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <h2 class="mb-0">О курсе</h2>
                        </div>
                        <div class="card-body">
                            <div class="description-content">
                                {!! $course->description !!}
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="mb-0">Учебный план</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Полный учебный план на {{ $course->duration * 4 }} недель доступен после регистрации.
                                <a href="{{ route('learning.plan', $course->slug) }}">Посмотреть демо-версию плана</a>
                            </div>

                            <div class="plan-preview">
                                <div class="dashboard-card">
                                    <div class="card-header" style="background: #e3f2fd;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">Базовый уровень (первые 4 недели)</h4>
                                            <span class="badge bg-primary">Недели 1-4</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="pricing-features">
                                            <li><i class="fas fa-check-circle text-success me-2"></i> Английский алфавит и произношение</li>
                                            <li><i class="fas fa-check-circle text-success me-2"></i> Базовые фразы приветствия</li>
                                            <li><i class="fas fa-check-circle text-success me-2"></i> Числа 1-100 и личные местоимения</li>
                                            <li><i class="fas fa-check-circle text-success me-2"></i> Глагол TO BE: am, is, are</li>
                                            <li><i class="fas fa-check-circle text-success me-2"></i> Present Simple: базовые конструкции</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('learning.plan', $course->slug) }}" class="btn-primary-new">
                                    <i class="fas fa-list"></i> Посмотреть полный учебный план
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Детали курса</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-level-up-alt me-2"></i> Уровень</span>
                                    <span class="badge bg-grenadine">{{ $course->level }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-clock me-2"></i> Длительность</span>
                                    <span>{{ $course->duration }} месяцев</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-book me-2"></i> Уроков</span>
                                    <span>{{ $course->lessons_count }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i> Размер группы</span>
                                    <span>{{ $course->group_size }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-calendar me-2"></i> Формат</span>
                                    <span>2 раза в неделю</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Что включено</h5>
                        </div>
                        <div class="card-body">
                            <ul class="pricing-features">
                                <li><i class="fas fa-check text-success me-2"></i> Живые занятия с преподавателем</li>
                                <li><i class="fas fa-check text-success me-2"></i> Все учебные материалы</li>
                                <li><i class="fas fa-check text-success me-2"></i> Домашние задания и проверка</li>
                                <li><i class="fas fa-check text-success me-2"></i> Доступ к платформе 24/7</li>
                                <li><i class="fas fa-check text-success me-2"></i> Сертификат по окончании</li>
                                <li><i class="fas fa-check text-success me-2"></i> Поддержка куратора</li>
                            </ul>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header bg-grenadine text-white">
                            <h5 class="mb-0">Стоимость</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="price-display-new mb-3">
                                <span class="price-main-new">{{ number_format($course->price, 0, '', ' ') }} ₽</span>
                                <span class="price-period-new">/месяц</span>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted">При оплате за 3 месяца</div>
                                <div class="text-success fw-bold">{{ number_format($course->price * 3 * 0.9, 0, '', ' ') }} ₽</div>
                                <small class="text-muted">Экономия {{ number_format($course->price * 3 * 0.1, 0, '', ' ') }} ₽</small>
                            </div>
                            <a href="/signup" class="btn-primary-new w-100">
                                Начать обучение
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Готовы начать обучение?</h2>
                    <p>Запишитесь на бесплатный пробный урок и получите индивидуальную программу обучения</p>
                    <div class="cta-buttons">
                        <a href="/signup" class="btn-primary-new">
                            Записаться на пробный урок
                        </a>
                        <a href="{{ route('courses') }}" class="btn-secondary-new">
                            Посмотреть все курсы
                        </a>
                    </div>
                </div>
                <div class="cta-visual">
                    <div class="cta-stat">
                        <div class="cta-stat-number">{{ $course->duration * 4 }}</div>
                        <div class="cta-stat-label">недель обучения</div>
                    </div>
                    <div class="cta-stat">
                        <div class="cta-stat-number">{{ $course->lessons_count }}</div>
                        <div class="cta-stat-label">интерактивных уроков</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
