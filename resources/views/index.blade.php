@extends('app')

@section('title', 'Knowly - Онлайн школа английского языка')

@section('content')

    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span><i class="fas fa-bullseye"></i> Старт курсов 15 января</span>
                </div>
                <h1 class="hero-title">
                    Английский
                    <span class="title-accent">с носителями</span>
                    языка онлайн
                </h1>
                <p class="hero-description">
                    Живое общение, персональная программа и гарантия результата.
                    Заговорите свободно через 3 месяца!
                </p>
                <div class="hero-buttons">
                    <a href="/signup" class="btn btn-primary">
                        <span class="btn-icon"><i class="fas fa-rocket"></i></span>
                        Начать бесплатно
                    </a>
                    <a href="/courses" class="btn btn-secondary">
                        <span class="btn-icon"><i class="fas fa-book"></i></span>
                        Смотреть курсы
                    </a>
                </div>
                <div class="hero-features">
                    <div class="hero-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Преподаватели из США и UK</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Трудоустройство после курса</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Занимайтесь с телефона</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="visual-container">
                    <div class="main-image-wrapper">
                        <div class="main-image">
                            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Обучение английскому">
                        </div>
                        <div class="image-glow"></div>
                    </div>


                    <div class="floating-cards-container">
                        <div class="floating-card card-1">
                            <div class="card-inner">
                                <div class="card-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div class="card-content">
                                    <h4>Живые уроки</h4>
                                    <p>Общение с преподавателями онлайн</p>
                                </div>
                            </div>
                        </div>

                        <div class="floating-card card-2">
                            <div class="card-inner">
                                <div class="card-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="card-content">
                                    <h4>Ваш прогресс</h4>
                                    <p>Отслеживайте рост и успехи</p>
                                </div>
                            </div>
                        </div>

                        <div class="floating-card card-3">
                            <div class="card-inner">
                                <div class="card-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="card-content">
                                    <h4>Сертификат</h4>
                                    <p>Международный документ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-background">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>
    </section>


    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="2500">0</div>
                        <div class="stat-label">Активных студентов</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-globe-americas"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="15">0</div>
                        <div class="stat-label">Стран обучения</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="98">0</div>
                        <div class="stat-label">% Успеваемости</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-content">
                        <div class="stat-number" data-count="5000">0</div>
                        <div class="stat-label">Часов обучения</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Почему Knowly — это эффективно</h2>
                <p class="section-subtitle">Современный подход к изучению языка, который действительно работает</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
                    </div>
                    <h3>Индивидуальная программа</h3>
                    <p>Алгоритм анализирует ваш уровень и цели, создавая персональный план обучения</p>
                    <div class="feature-decoration"></div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    </div>
                    <h3>Носители языка</h3>
                    <p>Преподаватели из Великобритании, США и Канады с международными сертификатами</p>
                    <div class="feature-decoration"></div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-stopwatch"></i></div>
                    </div>
                    <h3>Гибкое расписание</h3>
                    <p>Занимайтесь в любое время. Переносите уроки без потери качества обучения</p>
                    <div class="feature-decoration"></div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                    </div>
                    <h3>Мобильная платформа</h3>
                    <p>Учитесь с любого устройства. Все материалы синхронизируются автоматически</p>
                    <div class="feature-decoration"></div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                    <h3>Трекер прогресса</h3>
                    <p>Наглядная статистика успехов и персональные рекомендации для улучшения</p>
                    <div class="feature-decoration"></div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
                    </div>
                    <h3>Карьерный рост</h3>
                    <p>Помогаем с трудоустройством в международные компании после завершения курса</p>
                    <div class="feature-decoration"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="process">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Как проходит обучение</h2>
                <p class="section-subtitle">Простой и эффективный путь к свободному владению английским</p>
            </div>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Тестирование</h3>
                    <p>Определяем ваш текущий уровень и цели обучения</p>
                </div>
                <div class="process-step">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>План обучения</h3>
                    <p>Создаем индивидуальную программу под ваши задачи</p>
                </div>
                <div class="process-step">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h3>Занятия</h3>
                    <p>Регулярные уроки с носителями языка в удобное время</p>
                </div>
                <div class="process-step">
                    <div class="step-number">04</div>
                    <div class="step-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Анализ прогресса</h3>
                    <p>Отслеживаем успехи и корректируем программу</p>
                </div>
                <div class="process-step">
                    <div class="step-number">05</div>
                    <div class="step-icon"><i class="fas fa-trophy"></i></div>
                    <h3>Результат</h3>
                    <p>Достигаем поставленных целей и получаем сертификат</p>
                </div>
            </div>
        </div>
    </section>


    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Отзывы наших студентов</h2>
                <p class="section-subtitle">Что говорят те, кто уже заговорил на английском</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="student-avatar">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Мария">
                        </div>
                        <div class="student-info">
                            <h4>Мария Иванова</h4>
                            <p>Курс: Business English</p>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        "За 3 месяца занятий смогла уверенно вести переговоры с иностранными партнерами. Преподаватели - настоящие профессионалы!"
                    </p>
                    <div class="testimonial-footer">
                        <span class="result"><i class="fas fa-chart-line"></i> Результат: +40% к зарплате</span>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="student-avatar">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Алексей">
                        </div>
                        <div class="student-info">
                            <h4>Алексей Петров</h4>
                            <p>Курс: Разговорный английский</p>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        "Никогда не думал, что смогу свободно общаться за границей. Методика Knowly действительно работает!"
                    </p>
                    <div class="testimonial-footer">
                        <span class="result"><i class="fas fa-comments"></i> Результат: свободное общение</span>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="student-avatar">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Екатерина">
                        </div>
                        <div class="student-info">
                            <h4>Екатерина Смирнова</h4>
                            <p>Курс: Подготовка к IELTS</p>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        "Сдала IELTS на 8.0 благодаря персональному подходу и качественной подготовке. Спасибо команде Knowly!"
                    </p>
                    <div class="testimonial-footer">
                        <span class="result"><i class="fas fa-award"></i> Результат: IELTS 8.0</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="pricing">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Выберите свой курс</h2>
                <p class="section-subtitle">Доступные программы для разных целей и уровня подготовки</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Beginner</h3>
                        <div class="price">12 000 ₽<span>/месяц</span></div>
                        <p>Для начинающих с нуля</p>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 8 индивидуальных уроков</li>
                        <li><i class="fas fa-check"></i> Базовая грамматика</li>
                        <li><i class="fas fa-check"></i> Разговорная практика</li>
                        <li><i class="fas fa-check"></i> Доступ к материалам</li>
                        <li><i class="fas fa-times"></i> Разговорный клуб</li>
                    </ul>
                    <a href="/signup" class="btn btn-outline">Выбрать курс</a>
                </div>

                <div class="pricing-card popular">
                    <div class="popular-badge">Популярный</div>
                    <div class="pricing-header">
                        <h3>Pro</h3>
                        <div class="price">18 000 ₽<span>/месяц</span></div>
                        <p>Для уверенного общения</p>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 12 индивидуальных уроков</li>
                        <li><i class="fas fa-check"></i> Углубленная грамматика</li>
                        <li><i class="fas fa-check"></i> Разговорная практика</li>
                        <li><i class="fas fa-check"></i> Доступ к материалам</li>
                        <li><i class="fas fa-check"></i> Участие в разговорном клубе</li>
                    </ul>
                    <a href="/signup" class="btn btn-primary">Выбрать курс</a>
                </div>

                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Business</h3>
                        <div class="price">25 000 ₽<span>/месяц</span></div>
                        <p>Для карьерного роста</p>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 16 индивидуальных уроков</li>
                        <li><i class="fas fa-check"></i> Деловой английский</li>
                        <li><i class="fas fa-check"></i> Подготовка к собеседованию</li>
                        <li><i class="fas fa-check"></i> Персональный куратор</li>
                        <li><i class="fas fa-check"></i> Гарантия трудоустройства</li>
                    </ul>
                    <a href="/signup" class="btn btn-outline">Выбрать курс</a>
                </div>
            </div>
        </div>
    </section>


    <section class="faq">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Часто задаваемые вопросы</h2>
                <p class="section-subtitle">Ответы на самые популярные вопросы о нашем обучении</p>
            </div>
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <h4><i class="fas fa-question-circle"></i> Сколько времени нужно, чтобы заговорить на английском?</h4>
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>При регулярных занятиях 3-4 раза в неделю большинство наших студентов начинают уверенно общаться на бытовые темы уже через 2-3 месяца.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4><i class="fas fa-question-circle"></i> Можно ли поменять преподавателя?</h4>
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Да, вы можете поменять преподавателя в любой момент, если почувствуете, что стиль обучения вам не подходит.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4><i class="fas fa-question-circle"></i> Что если я пропущу урок?</h4>
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Вы можете перенести занятие, предупредив нас за 4 часа. Мы найдем удобное время для отработки.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4><i class="fas fa-question-circle"></i> Нужны ли дополнительные материалы?</h4>
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Все необходимые материалы предоставляются в рамках курса. Дополнительные покупки не требуются.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="cta">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Готовы заговорить на английском?</h2>
                    <p>Первый урок — бесплатно. Познакомьтесь с платформой и преподавателем без обязательств</p>
                    <div class="cta-buttons">
                        <a href="/signup" class="btn btn-primary">Начать обучение</a>
                        <a href="/courses" class="btn btn-outline-light">Посмотреть все курсы</a>
                    </div>
                </div>
                <div class="cta-visual">
                    <div class="cta-stat">
                        <div class="cta-stat-number">98%</div>
                        <div class="cta-stat-label">Студентов рекомендуют нас</div>
                    </div>
                    <div class="cta-stat">
                        <div class="cta-stat-number">4.9/5</div>
                        <div class="cta-stat-label">Средний рейтинг школы</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <span class="logo-icon"><i class="fas fa-graduation-cap"></i></span>
                        Knowly
                    </div>
                    <p class="footer-description">
                        Онлайн-школа английского языка нового поколения.
                        Инновационный подход к обучению с гарантией результата.
                    </p>
                </div>
                <div class="footer-section">
                    <h4>Курсы</h4>
                    <ul>
                        <li><a href="/courses"><i class="fas fa-chevron-right"></i> Все курсы</a></li>
                        <li><a href="/courses"><i class="fas fa-chevron-right"></i> Для начинающих</a></li>
                        <li><a href="/courses"><i class="fas fa-chevron-right"></i> Бизнес английский</a></li>
                        <li><a href="/courses"><i class="fas fa-chevron-right"></i> Подготовка к экзаменам</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Компания</h4>
                    <ul>
                        <li><a href="/about"><i class="fas fa-chevron-right"></i> О нас</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Преподаватели</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Отзывы</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Блог</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Контакты</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> info@knowly.ru</p>
                        <p><i class="fas fa-phone"></i> +7 (999) 999-99-99</p>
                        <p><i class="fas fa-clock"></i> Ежедневно 9:00-21:00</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Knowly. Все права защищены.</p>
            </div>
        </div>
    </footer>
@endsection

@section('scripts')
    <script>

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


                    if (target === 98) {
                        counter.textContent = currentValue + '%';
                    } else if (target === 15) {
                        counter.textContent = currentValue;
                    } else {
                        counter.textContent = currentValue + '+';
                    }


                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {

                        if (target === 98) {
                            counter.textContent = target + '%';
                        } else if (target === 15) {
                            counter.textContent = target;
                        } else {
                            counter.textContent = target + '+';
                        }
                    }
                }


                requestAnimationFrame(updateCounter);
            });
        }


        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const toggle = question.querySelector('.faq-toggle i');

                if (answer.style.maxHeight) {
                    answer.style.maxHeight = null;
                    toggle.className = 'fas fa-plus';
                } else {
                    answer.style.maxHeight = answer.scrollHeight + "px";
                    toggle.className = 'fas fa-minus';
                }
            });
        });
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
            }, {
                threshold: 0.5
            });

            observer.observe(statsSection);
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', function() {
            initScrollAnimation();
        });

        // Резервный запуск через 3 секунды
        setTimeout(() => {
            const counters = document.querySelectorAll('.stat-number[data-count]');
            const anyAnimated = Array.from(counters).some(counter => {
                return parseInt(counter.textContent) > 0;
            });

            if (!anyAnimated) {
                animateCounters();
            }
        }, 3000);
    </script>
@endsection
