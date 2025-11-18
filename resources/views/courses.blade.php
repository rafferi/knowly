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
                <button class="btn btn-outline active" data-filter="all">Все курсы</button>
                <button class="btn btn-outline" data-filter="beginner">Для начинающих</button>
                <button class="btn btn-outline" data-filter="business">Бизнес английский</button>
                <button class="btn btn-outline" data-filter="conversational">Разговорный</button>
                <button class="btn btn-outline" data-filter="exam">Подготовка к экзаменам</button>
                <button class="btn btn-outline" data-filter="specialized">Специализированные</button>
            </div>

            <div class="courses-grid">

                <div class="pricing-card" data-category="beginner">
                    <div class="pricing-header">
                        <h3>English Starter</h3>
                        <div class="price">8 900 ₽<span>/месяц</span></div>
                        <p>Для начинающих с нуля</p>
                        <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">Уровень A1-A2</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 8 уроков в месяц</li>
                        <li><i class="fas fa-check"></i> Группа 4-6 человек</li>
                        <li><i class="fas fa-check"></i> Базовая грамматика</li>
                        <li><i class="fas fa-check"></i> Произношение и аудирование</li>
                        <li><i class="fas fa-check"></i> Разговорная практика</li>
                        <li><i class="fas fa-check"></i> Все учебные материалы</li>
                        <li><i class="fas fa-times"></i> Разговорный клуб</li>
                        <li><i class="fas fa-times"></i> Персональный куратор</li>
                    </ul>
                    <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                        <span><i class="fas fa-clock"></i> 3 месяца</span>
                        <span><i class="fas fa-users"></i> 4-6 студентов</span>
                        <span><i class="fas fa-play-circle"></i> 24 урока</span>
                    </div>
                    <a href="/signup" class="btn btn-outline" style="width: 100%; text-align: center;">Бесплатный пробный урок</a>
                </div>


                <div class="pricing-card popular" data-category="conversational">
                    <div class="popular-badge">Популярный</div>
                    <div class="pricing-header">
                        <h3>Conversational Pro</h3>
                        <div class="price">12 900 ₽<span>/месяц</span></div>
                        <p>Свободное общение</p>
                        <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">Уровень A2-B1</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 12 уроков в месяц</li>
                        <li><i class="fas fa-check"></i> Группа 3-5 человек</li>
                        <li><i class="fas fa-check"></i> Углубленная грамматика</li>
                        <li><i class="fas fa-check"></i> Живые диалоги и обсуждения</li>
                        <li><i class="fas fa-check"></i> Сленг и идиомы</li>
                        <li><i class="fas fa-check"></i> Все учебные материалы</li>
                        <li><i class="fas fa-check"></i> Участие в разговорном клубе</li>
                        <li><i class="fas fa-times"></i> Персональный куратор</li>
                    </ul>
                    <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                        <span><i class="fas fa-clock"></i> 4 месяца</span>
                        <span><i class="fas fa-users"></i> 3-5 студентов</span>
                        <span><i class="fas fa-play-circle"></i> 48 уроков</span>
                    </div>
                    <a href="/signup" class="btn btn-primary" style="width: 100%; text-align: center;">Бесплатный пробный урок</a>
                </div>


                <div class="pricing-card" data-category="business">
                    <div class="pricing-header">
                        <h3>Business English</h3>
                        <div class="price">16 900 ₽<span>/месяц</span></div>
                        <p>Для работы и карьеры</p>
                        <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">Уровень B1-B2</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 12 уроков в месяц</li>
                        <li><i class="fas fa-check"></i> Группа 2-4 человека</li>
                        <li><i class="fas fa-check"></i> Деловая переписка</li>
                        <li><i class="fas fa-check"></i> Презентации и переговоры</li>
                        <li><i class="fas fa-check"></i> Телефонные разговоры</li>
                        <li><i class="fas fa-check"></i> Все учебные материалы</li>
                        <li><i class="fas fa-check"></i> Участие в разговорном клубе</li>
                        <li><i class="fas fa-check"></i> Поддержка куратора</li>
                    </ul>
                    <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                        <span><i class="fas fa-clock"></i> 5 месяцев</span>
                        <span><i class="fas fa-users"></i> 2-4 студента</span>
                        <span><i class="fas fa-play-circle"></i> 60 уроков</span>
                    </div>
                    <a href="/signup" class="btn btn-outline" style="width: 100%; text-align: center;">Бесплатный пробный урок</a>
                </div>


                <div class="pricing-card" data-category="exam">
                    <div class="pricing-header">
                        <h3>IELTS Preparation</h3>
                        <div class="price">18 900 ₽<span>/месяц</span></div>
                        <p>Подготовка к экзамену</p>
                        <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">Уровень B2-C1</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 16 уроков в месяц</li>
                        <li><i class="fas fa-check"></i> Индивидуальные занятия</li>
                        <li><i class="fas fa-check"></i> Полная подготовка к IELTS</li>
                        <li><i class="fas fa-check"></i> Пробные тесты каждую неделю</li>
                        <li><i class="fas fa-check"></i> Стратегии сдачи экзамена</li>
                        <li><i class="fas fa-check"></i> Все учебные материалы</li>
                        <li><i class="fas fa-check"></i> Разбор ошибок</li>
                        <li><i class="fas fa-check"></i> Персональный куратор</li>
                    </ul>
                    <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                        <span><i class="fas fa-clock"></i> 3 месяца</span>
                        <span><i class="fas fa-users"></i> Индивидуально</span>
                        <span><i class="fas fa-play-circle"></i> 48 уроков</span>
                    </div>
                    <a href="/signup" class="btn btn-outline" style="width: 100%; text-align: center;">Бесплатный пробный урок</a>
                </div>


                <div class="pricing-card" data-category="specialized">
                    <div class="pricing-header">
                        <h3>IT English</h3>
                        <div class="price">14 900 ₽<span>/месяц</span></div>
                        <p>Для IT-специалистов</p>
                        <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">Уровень B1-C1</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 10 уроков в месяц</li>
                        <li><i class="fas fa-check"></i> Группа 3-5 человек</li>
                        <li><i class="fas fa-check"></i> Техническая лексика</li>
                        <li><i class="fas fa-check"></i> Подготовка к собеседованиям</li>
                        <li><i class="fas fa-check"></i> Документация и код-ревью</li>
                        <li><i class="fas fa-check"></i> Все учебные материалы</li>
                        <li><i class="fas fa-check"></i> Участие в IT разговорном клубе</li>
                        <li><i class="fas fa-check"></i> Поддержка куратора</li>
                    </ul>
                    <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                        <span><i class="fas fa-clock"></i> 4 месяца</span>
                        <span><i class="fas fa-users"></i> 3-5 студентов</span>
                        <span><i class="fas fa-play-circle"></i> 40 уроков</span>
                    </div>
                    <a href="/signup" class="btn btn-outline" style="width: 100%; text-align: center;">Бесплатный пробный урок</a>
                </div>


                <div class="pricing-card" data-category="conversational">
                    <div class="pricing-header">
                        <h3>Travel English</h3>
                        <div class="price">9 900 ₽<span>/месяц</span></div>
                        <p>Для путешествий</p>
                        <div class="level-badge" style="background: var(--grenadine); color: white; padding: 0.3rem 1rem; border-radius: 15px; font-size: 0.8rem; display: inline-block;">Уровень A1-B1</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 8 уроков в месяц</li>
                        <li><i class="fas fa-check"></i> Группа 4-6 человек</li>
                        <li><i class="fas fa-check"></i> Лексика для путешествий</li>
                        <li><i class="fas fa-check"></i> Аэропорт, отель, ресторан</li>
                        <li><i class="fas fa-check"></i> Ситуации экстренной помощи</li>
                        <li><i class="fas fa-check"></i> Все учебные материалы</li>
                        <li><i class="fas fa-check"></i> Разговорный клуб путешественников</li>
                        <li><i class="fas fa-times"></i> Персональный куратор</li>
                    </ul>
                    <div class="course-stats" style="display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 0.9rem; color: var(--ultimate-gray);">
                        <span><i class="fas fa-clock"></i> 2 месяца</span>
                        <span><i class="fas fa-users"></i> 4-6 студентов</span>
                        <span><i class="fas fa-play-circle"></i> 16 уроков</span>
                    </div>
                    <a href="/signup" class="btn btn-outline" style="width: 100%; text-align: center;">Бесплатный пробный урок</a>
                </div>
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

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {

                    filterButtons.forEach(btn => btn.classList.remove('active'));

                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');


                    courseCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, 50);
                        } else {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });

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
