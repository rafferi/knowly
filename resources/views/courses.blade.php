@extends('app')

@section('title', 'Курсы английского языка - Knowly')

@section('content')

    <section class="courses-hero-new">
        <div class="courses-hero-container-new">
            <div class="courses-hero-content-new">
                <h1 class="courses-hero-title-new">
                    Выберите свой идеальный курс английского
                </h1>
                <p class="courses-hero-description-new">
                    От начинающего до продвинутого уровня. Живое общение с носителями языка,
                    персональный подход и гарантия результата для каждой цели.
                </p>

                <div class="courses-hero-buttons-new">
                    <a href="#all-courses" class="btn btn-primary-new">
                        Смотреть все курсы
                    </a>
                    <a href="#quiz" class="btn btn-secondary-new">
                        Подобрать курс
                    </a>
                </div>


                <div class="levels-progress-new">
                    <div class="levels-track-new">
                        <div class="level-marker-new" data-level="A1">
                            <span class="level-name-new">A1</span>
                        </div>
                        <div class="level-marker-new" data-level="A2">
                            <span class="level-name-new">A2</span>
                        </div>
                        <div class="level-marker-new active-new" data-level="B1">
                            <span class="level-name-new">B1</span>
                        </div>
                        <div class="level-marker-new" data-level="B2">
                            <span class="level-name-new">B2</span>
                        </div>
                        <div class="level-marker-new" data-level="C1">
                            <span class="level-name-new">C1</span>
                        </div>
                        <div class="level-marker-new" data-level="C2">
                            <span class="level-name-new">C2</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="courses-hero-visual-new">
                <div class="main-course-card-new">
                    <div class="course-header-new">
                        <div class="course-badge-new">Разговорный</div>
                        <div class="course-level-new">A2-B1</div>
                    </div>
                    <div class="course-progress-new">
                        <div class="progress-info-new">
                            <span class="progress-text-new">65% завершено</span>
                        </div>
                        <div class="progress-bar-new">
                            <div class="progress-fill-new" style="width: 65%"></div>
                        </div>
                    </div>
                </div>


                <div class="floating-course-new course-1-new">
                    <div class="course-icon-new">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="course-info-new">
                        <h4>Business English</h4>
                        <p>Уровень B1-B2</p>
                    </div>
                </div>

                <div class="floating-course-new course-2-new">
                    <div class="course-icon-new">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="course-info-new">
                        <h4>IELTS</h4>
                        <p>B2-C1</p>
                    </div>
                </div>

                <div class="floating-course-new course-3-new">
                    <div class="course-icon-new">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div class="course-info-new">
                        <h4>Для путешествий</h4>
                        <p>A1-B1</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="quiz" class="features" style="padding: 4rem 0;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Не знаете с чего начать?</h2>
                <p class="section-subtitle">Пройдите 2-минутный тест и мы подберем идеальный курс для ваших целей</p>
            </div>

            <div class="quiz-container" style="max-width: 800px; margin: 0 auto;">
                <div class="quiz-progress" style="background: var(--nimbus-cloud); height: 6px; border-radius: 3px; margin-bottom: 3rem; position: relative;">
                    <div class="quiz-progress-fill" style="background: var(--grenadine); height: 100%; border-radius: 3px; width: 0%; transition: width 0.3s ease;"></div>
                </div>

                <div class="quiz-step active">
                    <h3 style="text-align: center; margin-bottom: 2rem; color: var(--black-beauty);">Какова ваша основная цель изучения английского?</h3>
                    <div class="quiz-options" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div class="quiz-option" style="padding: 1.5rem; background: var(--white); border: 2px solid var(--nimbus-cloud); border-radius: 15px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fas fa-briefcase" style="font-size: 2rem; color: var(--grenadine); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Работа и карьера</h4>
                            <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Для бизнеса, переговоров, работы в международной компании</p>
                        </div>
                        <div class="quiz-option" style="padding: 1.5rem; background: var(--white); border: 2px solid var(--nimbus-cloud); border-radius: 15px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fas fa-plane" style="font-size: 2rem; color: var(--grenadine); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Путешествия</h4>
                            <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Для комфортного общения за границей, отелей, ресторанов</p>
                        </div>
                        <div class="quiz-option" style="padding: 1.5rem; background: var(--white); border: 2px solid var(--nimbus-cloud); border-radius: 15px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem; color: var(--grenadine); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Учеба и экзамены</h4>
                            <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Для поступления в вузы, IELTS, TOEFL, других экзаменов</p>
                        </div>
                        <div class="quiz-option" style="padding: 1.5rem; background: var(--white); border: 2px solid var(--nimbus-cloud); border-radius: 15px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fas fa-comments" style="font-size: 2rem; color: var(--grenadine); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Повседневное общение</h4>
                            <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Для друзей, хобби, сериалов и понимания культуры</p>
                        </div>
                    </div>
                </div>

                <div class="quiz-actions" style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-primary" onclick="nextQuizStep()">
                        <i class="fas fa-arrow-right"></i> Далее
                    </button>
                </div>
            </div>
        </div>
    </section>


    <section id="all-courses" class="pricing" style="background: var(--gray-light); padding: 6rem 0;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Все курсы английского языка</h2>
                <p class="section-subtitle">Выберите программу, которая подходит именно вам. Все курсы включают бесплатный пробный урок</p>
            </div>


            <div class="courses-filters" style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 3rem; flex-wrap: wrap;">
                <button class="btn btn-outline {{ $filter === 'all' ? 'active' : '' }}" data-filter="all">Все курсы</button>
                <button class="btn btn-outline {{ $filter === 'beginner' ? 'active' : '' }}" data-filter="beginner">Для начинающих</button>
                <button class="btn btn-outline {{ $filter === 'business' ? 'active' : '' }}" data-filter="business">Бизнес английский</button>
                <button class="btn btn-outline {{ $filter === 'conversational' ? 'active' : '' }}" data-filter="conversational">Разговорный</button>
                <button class="btn btn-outline {{ $filter === 'exam' ? 'active' : '' }}" data-filter="exam">Подготовка к экзаменам</button>
                <button class="btn btn-outline {{ $filter === 'specialized' ? 'active' : '' }}" data-filter="specialized">Специализированные</button>
            </div>

            <div class="courses-grid">
                @foreach($courses as $course)
                    <div class="pricing-card {{ $course->popular ? 'popular' : '' }}" data-category="{{ $course->course_type }}">
                        @if($course->popular)
                            <div class="popular-badge">Популярный</div>
                        @endif

                        <div class="pricing-header">
                            <h3>{{ $course->title }}</h3>
                            <div class="price">{{ number_format($course->price, 0, '', ' ') }} ₽<span>/месяц</span></div>
                            <p>{{ $course->short_description }}</p>
                            <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">
                                Уровень {{ $course->level }}
                            </div>
                        </div>

                        <ul class="pricing-features">
                            @foreach(json_decode($course->features) as $feature)
                                <li><i class="fas fa-check"></i> {{ $feature }}</li>
                            @endforeach
                        </ul>

                        <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                            <span><i class="fas fa-clock"></i> {{ $course->duration }} месяцев</span>
                            <span><i class="fas fa-users"></i> {{ $course->group_size }}</span>
                            <span><i class="fas fa-play-circle"></i> {{ $course->lessons_count }} уроков</span>
                        </div>

                            <div class="course-actions">
                                <a href="/signup" class="btn {{ $course->popular ? 'btn-primary' : 'btn-outline' }} btn-full">
                                    Бесплатный пробный урок
                                </a>
                                <div class="btn-row">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary">
                                        Подробнее
                                    </a>
                                    <a href="{{ route('learning.plan', $course->slug) }}" class="btn btn-plan">
                                        <i class="fas fa-graduation-cap"></i> Учебный план
                                    </a>
                                </div>
                            </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section class="features" style="padding: 6rem 0;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Почему студенты выбирают Knowly</h2>
                <p class="section-subtitle">Мы создали образовательную платформу, которая действительно помогает заговорить на английском</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
                    </div>
                    <h3>Преподаватели-носители</h3>
                    <p>Все наши преподаватели - сертифицированные специалисты из США, Великобритании и Канады с опытом работы от 3 лет</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                    <h3>Измеримый прогресс</h3>
                    <p>Трекер прогресса, регулярное тестирование и персональные отчеты помогут видеть ваш рост каждую неделю</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-comments"></i></div>
                    </div>
                    <h3>Разговорная практика</h3>
                    <p>80% каждого урока - это живое общение. Мы ломаем языковой барьер с первого занятия</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                    </div>
                    <h3>Удобная платформа</h3>
                    <p>Занимайтесь с любого устройства. Все материалы, домашние задания и расписание в одном месте</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-graduation-cap"></i></div>
                    </div>
                    <h3>Сертификат</h3>
                    <p>По окончании курса вы получаете сертификат международного образца с указанием достигнутого уровня</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-hand-holding-usd"></i></div>
                    </div>
                    <h3>Гарантия результата</h3>
                    <p>Если после курса вы не достигли заявленных целей - мы вернем деньги или предоставим дополнительные занятия</p>
                </div>
            </div>
        </div>
    </section>


    <section class="cta">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Готовы начать обучение?</h2>
                    <p>Запишитесь на бесплатный пробный урок к преподавателю-носителю и получите персональную программу обучения</p>
                    <div class="cta-buttons">
                        <a href="/signup" class="btn btn-primary">Записаться на пробный урок</a>
                        <a href="#quiz" class="btn btn-outline-light">Подобрать курс</a>
                    </div>
                </div>
                <div class="cta-visual">
                    <div class="cta-stat">
                        <div class="cta-stat-number">98%</div>
                        <div class="cta-stat-label">Студентов рекомендуют курсы</div>
                    </div>
                    <div class="cta-stat">
                        <div class="cta-stat-number">4.8/5</div>
                        <div class="cta-stat-label">Средняя оценка преподавателей</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.courses-filters .btn');
            const courseCards = document.querySelectorAll('.pricing-card');

            // Обработка кликов по фильтрам
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    const url = new URL(window.location);

                    if (filter === 'all') {
                        url.searchParams.delete('filter');
                    } else {
                        url.searchParams.set('filter', filter);
                    }

                    window.location.href = url.toString();
                });
            });

            // Анимация появления карточек
            courseCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + index * 100);
            });

            const levelMarkers = document.querySelectorAll('.level-marker');
            levelMarkers.forEach(marker => {
                marker.addEventListener('mouseenter', function() {
                    levelMarkers.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });

        function nextQuizStep() {
            const progressFill = document.querySelector('.quiz-progress-fill');
            progressFill.style.width = '50%';

            setTimeout(() => {
                alert('Спасибо за ответ! В реальной версии здесь будет следующий вопрос квиза.');
            }, 500);
        }

        document.querySelectorAll('.quiz-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.quiz-option').forEach(opt => {
                    opt.style.borderColor = 'var(--nimbus-cloud)';
                    opt.style.backgroundColor = 'var(--white)';
                });

                this.style.borderColor = 'var(--grenadine)';
                this.style.backgroundColor = 'rgba(223, 63, 50, 0.05)';
            });
        });
    </script>
@endsection
