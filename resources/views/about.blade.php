@extends('app')

@section('title', 'О нас - Knowly - Онлайн школа английского языка')

@section('content')

    <section class="about-hero-new">
        <div class="about-hero-container-new">
            <div class="about-hero-content-new">
                <div class="about-badge-new">
                    <span><i class="fas fa-heart"></i> Knowly с 2020 года</span>
                </div>

                <h1 class="about-hero-title-new">
                    Мы меняем подход
                    <span class="about-title-accent-new">к изучению</span>
                    английского
                </h1>

                <p class="about-hero-description-new">
                    Knowly - это не просто онлайн-школа. Это сообщество людей, которые верят,
                    что каждый может заговорить на английском, независимо от возраста и начального уровня.
                </p>

                <div class="about-hero-buttons-new">
                    <a href="/courses" class="btn btn-primary-about">
                        <span class="btn-icon"><i class="fas fa-book"></i></span>
                        Наши курсы
                    </a>
                    <a href="#team" class="btn btn-secondary-about">
                        <span class="btn-icon"><i class="fas fa-users"></i></span>
                        Наша команда
                    </a>
                </div>
                <div class="about-stats-new">
                    <div class="stat-card-about">
                        <div class="stat-icon-about">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-content-about">
                            <div class="stat-number-about" data-count="4000">0</div>
                            <div class="stat-label-about">выпускников</div>
                        </div>
                    </div>

                    <div class="stat-card-about">
                        <div class="stat-icon-about">
                            <i class="fas fa-globe-americas"></i>
                        </div>
                        <div class="stat-content-about">
                            <div class="stat-number-about" data-count="15">0</div>
                            <div class="stat-label-about">стран обучения</div>
                        </div>
                    </div>

                    <div class="stat-card-about">
                        <div class="stat-icon-about">
                            <i class="fas fa-smile"></i>
                        </div>
                        <div class="stat-content-about">
                            <div class="stat-number-about" data-count="98">0</div>
                            <div class="stat-label-about">довольных студентов</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-hero-visual-new">

                <div class="visual-main-new">
                    <div class="main-circle-new">
                        <div class="circle-content-new">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Knowly</span>
                        </div>
                        <div class="circle-glow-new"></div>
                    </div>
                </div>


                <div class="floating-element-new element-1-new">
                    <div class="element-icon-new">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="element-content-new">
                        <h4>Инновации</h4>
                        <p>Современные методики обучения</p>
                    </div>
                    <div class="element-dots-new"></div>
                </div>

                <div class="floating-element-new element-2-new">
                    <div class="element-icon-new">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="element-content-new">
                        <h4>Экспертиза</h4>
                        <p>Опыт работы 4+ года</p>
                    </div>
                    <div class="element-dots-new"></div>
                </div>

                <div class="floating-element-new element-3-new">
                    <div class="element-icon-new">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div class="element-content-new">
                        <h4>Поддержка</h4>
                        <p>Забота о каждом студенте</p>
                    </div>
                    <div class="element-dots-new"></div>
                </div>


                <div class="decoration-item-new decor-1-new">
                    <div class="decor-circle-new"></div>
                </div>
                <div class="decoration-item-new decor-2-new">
                    <div class="decor-square-new"></div>
                </div>
                <div class="decoration-item-new decor-3-new">
                    <div class="decor-triangle-new"></div>
                </div>
            </div>
        </div>

        <!-- Фон с анимированными формами -->
        <div class="about-hero-background-new">
            <div class="about-bg-shape-new shape-1-new"></div>
            <div class="about-bg-shape-new shape-2-new"></div>
            <div class="about-bg-shape-new shape-3-new"></div>
            <div class="about-bg-shape-new shape-4-new"></div>
        </div>
    </section>


    <section class="features" style="padding: 6rem 0;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Наша миссия и ценности</h2>
                <p class="section-subtitle">Мы создаем образовательную среду, где каждый может раскрыть свой потенциал в изучении английского языка</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
                    </div>
                    <h3>Наша миссия</h3>
                    <p>Сделать качественное образование доступным для всех, кто хочет свободно говорить на английском и открывать новые возможности в жизни и карьере.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-eye"></i></div>
                    </div>
                    <h3>Наше видение</h3>
                    <p>Стать ведущей онлайн-школой английского языка в русскоязычном пространстве, известной своими инновационными подходами и выдающимися результатами студентов.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-star"></i></div>
                    </div>
                    <h3>Наши ценности</h3>
                    <p>Качество, инновации, забота о студентах, прозрачность и постоянное развитие. Мы верим, что обучение должно приносить радость и реальные результаты.</p>
                </div>
            </div>
        </div>
    </section>


    <section class="process" style="background: var(--gray-light);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Наша история</h2>
                <p class="section-subtitle">От маленького проекта до ведущей онлайн-школы с тысячами выпускников</p>
            </div>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">2020</div>
                    <div class="step-icon"><i class="fas fa-seedling"></i></div>
                    <h3>Основание</h3>
                    <p>Knowly начинается как небольшой проект трех энтузиастов с целью сделать изучение английского доступным и эффективным</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2021</div>
                    <div class="step-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Рост</h3>
                    <p>Первые 1000 студентов, расширение команды преподавателей и запуск собственной образовательной платформы</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2022</div>
                    <div class="step-icon"><i class="fas fa-globe"></i></div>
                    <h3>Экспансия</h3>
                    <p>Выход на международный рынок, студенты из 15 стран и партнерства с международными образовательными организациями</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2023</div>
                    <div class="step-icon"><i class="fas fa-award"></i></div>
                    <h3>Признание</h3>
                    <p>Премия "Лучшая онлайн-школа года" и более 4000 успешных выпускников, свободно говорящих на английском</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2024</div>
                    <div class="step-icon"><i class="fas fa-rocket"></i></div>
                    <h3>Инновации</h3>
                    <p>Запуск AI-ассистента, мобильного приложения и программ трудоустройства для выпускников</p>
                </div>
            </div>
        </div>
    </section>


    <section id="team" class="pricing" style="padding: 6rem 0;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Наша команда</h2>
                <p class="section-subtitle">Профессионалы с международным опытом, которые горят своим делом</p>
            </div>

            <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <!-- Основатель 1 -->
                <div class="team-card" style="background: var(--white); padding: 2rem; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(39, 39, 42, 0.1);">
                    <div class="team-avatar" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1.5rem; overflow: hidden; border: 4px solid var(--grenadine);">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Алексей Петров" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 style="color: var(--black-beauty); margin-bottom: 0.5rem;">Алексей Петров</h3>
                    <p style="color: var(--grenadine); font-weight: 600; margin-bottom: 1rem;">Основатель и CEO</p>
                    <p style="color: var(--ultimate-gray); line-height: 1.6; margin-bottom: 1.5rem;">
                        Выпускник Cambridge University с 10-летним опытом преподавания. Создал Knowly с целью сделать качественное образование доступным для всех.
                    </p>
                    <div class="team-social" style="display: flex; justify-content: center; gap: 1rem;">
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-linkedin"></i></a>
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-telegram"></i></a>
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Основатель 2 -->
                <div class="team-card" style="background: var(--white); padding: 2rem; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(39, 39, 42, 0.1);">
                    <div class="team-avatar" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1.5rem; overflow: hidden; border: 4px solid var(--grenadine);">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Мария Иванова" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 style="color: var(--black-beauty); margin-bottom: 0.5rem;">Мария Иванова</h3>
                    <p style="color: var(--grenadine); font-weight: 600; margin-bottom: 1rem;">Academic Director</p>
                    <p style="color: var(--ultimate-gray); line-height: 1.6; margin-bottom: 1.5rem;">
                        Магистр лингвистики Oxford University. Разрабатывает учебные программы и курирует методическое направление школы.
                    </p>
                    <div class="team-social" style="display: flex; justify-content: center; gap: 1rem;">
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-linkedin"></i></a>
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-telegram"></i></a>
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Методист -->
                <div class="team-card" style="background: var(--white); padding: 2rem; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(39, 39, 42, 0.1);">
                    <div class="team-avatar" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1.5rem; overflow: hidden; border: 4px solid var(--grenadine);">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Дмитрий Смирнов" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 style="color: var(--black-beauty); margin-bottom: 0.5rem;">Дмитрий Смирнов</h3>
                    <p style="color: var(--grenadine); font-weight: 600; margin-bottom: 1rem;">Lead Methodologist</p>
                    <p style="color: var(--ultimate-gray); line-height: 1.6; margin-bottom: 1.5rem;">
                        Эксперт в области EdTech и современных методик обучения. Отвечает за внедрение инноваций в образовательный процесс.
                    </p>
                    <div class="team-social" style="display: flex; justify-content: center; gap: 1rem;">
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-linkedin"></i></a>
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-telegram"></i></a>
                        <a href="#" style="color: var(--ultimate-gray); transition: color 0.3s ease;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <!-- Преподаватели -->
            <div style="margin-top: 4rem;">
                <h3 style="text-align: center; color: var(--black-beauty); margin-bottom: 3rem; font-size: 1.8rem;">Наши преподаватели</h3>
                <div class="teachers-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="teacher-card" style="text-align: center;">
                        <div class="teacher-avatar" style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 1rem; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Sarah Johnson" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h4 style="color: var(--black-beauty); margin-bottom: 0.3rem;">Sarah Johnson</h4>
                        <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Носитель из США</p>
                        <p style="color: var(--grenadine); font-size: 0.8rem; font-weight: 600;">CELTA, 6 лет опыта</p>
                    </div>

                    <div class="teacher-card" style="text-align: center;">
                        <div class="teacher-avatar" style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 1rem; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Michael Brown" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h4 style="color: var(--black-beauty); margin-bottom: 0.3rem;">Michael Brown</h4>
                        <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Носитель из UK</p>
                        <p style="color: var(--grenadine); font-size: 0.8rem; font-weight: 600;">DELTA, 8 лет опыта</p>
                    </div>

                    <div class="teacher-card" style="text-align: center;">
                        <div class="teacher-avatar" style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 1rem; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Emily Davis" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h4 style="color: var(--black-beauty); margin-bottom: 0.3rem;">Emily Davis</h4>
                        <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Носитель из Канады</p>
                        <p style="color: var(--grenadine); font-size: 0.8rem; font-weight: 600;">TESOL, 5 лет опыта</p>
                    </div>

                    <div class="teacher-card" style="text-align: center;">
                        <div class="teacher-avatar" style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 1rem; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="David Wilson" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h4 style="color: var(--black-beauty); margin-bottom: 0.3rem;">David Wilson</h4>
                        <p style="color: var(--ultimate-gray); font-size: 0.9rem;">Носитель из Австралии</p>
                        <p style="color: var(--grenadine); font-size: 0.8rem; font-weight: 600;">TEFL, 7 лет опыта</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Достижения в цифрах -->
    <section class="stats-section" style="background: linear-gradient(135deg, var(--black-beauty) 0%, #1a1a1c 100%); color: var(--white);">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon" style="color: var(--grenadine);"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="4000" style="color: var(--grenadine);">0</div>
                        <div class="stat-label">Выпускников</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="color: var(--grenadine);"><i class="fas fa-globe-americas"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="15" style="color: var(--grenadine);">0</div>
                        <div class="stat-label">Стран обучения</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="color: var(--grenadine);"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="25" style="color: var(--grenadine);">0</div>
                        <div class="stat-label">Преподавателей</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="color: var(--grenadine);"><i class="fas fa-award"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="98" style="color: var(--grenadine);">0</div>
                        <div class="stat-label">% Успеваемости</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Принципы работы -->
    <section class="features" style="padding: 6rem 0; background: var(--gray-light);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Наши принципы работы</h2>
                <p class="section-subtitle">Мы придерживаемся четких принципов, которые обеспечивают высокое качество обучения</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-user-check"></i></div>
                    </div>
                    <h3>Индивидуальный подход</h3>
                    <p>Каждый студент уникален. Мы создаем персональные программы обучения, учитывая цели, темп и стиль обучения.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    </div>
                    <h3>Гарантия результата</h3>
                    <p>Мы настолько уверены в эффективности нашей методики, что предоставляем гарантию возврата средств при не достижении целей.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-sync-alt"></i></div>
                    </div>
                    <h3>Постоянное развитие</h3>
                    <p>Мы регулярно обновляем программы, внедряем новые технологии и повышаем квалификацию преподавателей.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-hand-holding-heart"></i></div>
                    </div>
                    <h3>Поддержка 24/7</h3>
                    <p>Наши кураторы всегда на связи, чтобы помочь с любыми вопросами и поддержать мотивацию.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                    <h3>Прозрачность прогресса</h3>
                    <p>Регулярные отчеты и трекер прогресса позволяют видеть результаты каждого урока.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-users"></i></div>
                    </div>
                    <h3>Сообщество</h3>
                    <p>Мы создаем поддерживающее сообщество, где студенты могут общаться и практиковать язык вместе.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Присоединяйтесь к Knowly</h2>
                    <p>Станьте частью нашего сообщества и начните свой путь к свободному владению английским с лучшими преподавателями</p>
                    <div class="cta-buttons">
                        <a href="/signup" class="btn btn-primary">Начать обучение</a>
                        <a href="/courses" class="btn btn-outline-light">Выбрать курс</a>
                    </div>
                </div>
                <div class="cta-visual">
                    <div class="cta-stat">
                        <div class="cta-stat-number">4.9/5</div>
                        <div class="cta-stat-label">Рейтинг школы</div>
                    </div>
                    <div class="cta-stat">
                        <div class="cta-stat-number">4000+</div>
                        <div class="cta-stat-label">Довольных студентов</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Анимация счетчиков
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number[data-count]');

            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const duration = 2000;
                const startTime = Date.now();
                const startValue = 0;

                function updateCounter() {
                    const currentTime = Date.now();
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    const currentValue = Math.floor(startValue + (target - startValue) * easeOutQuart);

                    counter.textContent = currentValue + (target === 98 ? '%' : target === 15 ? '' : '+');

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target + (target === 98 ? '%' : target === 15 ? '' : '+');
                    }
                }

                requestAnimationFrame(updateCounter);
            });
        }

        // Запуск анимации при скролле
        function initScrollAnimation() {
            const statsSection = document.querySelector('.stats-section');
            if (!statsSection) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(statsSection);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initScrollAnimation();

            // Плавная прокрутка для якорных ссылок
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Резервный запуск анимации
        setTimeout(() => {
            const counters = document.querySelectorAll('.stat-number[data-count]');
            const anyAnimated = Array.from(counters).some(counter => parseInt(counter.textContent) > 0);
            if (!anyAnimated) animateCounters();
        }, 3000);
    </script>

    <style>
        .team-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }

        .team-social a:hover {
            color: var(--grenadine) !important;
        }

        .teacher-card {
            transition: transform 0.3s ease;
        }

        .teacher-card:hover {
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: 1fr;
            }

            .teachers-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .teachers-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
