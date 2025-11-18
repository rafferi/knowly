@extends('app')

@section('title', 'Тест на подбор курса - Knowly')

@section('content')
    <div class="placement-test-container">
        <div class="profile-card placement-test-card">
            <div class="placement-test-header">
                <div class="test-title">Какой английский вам подходит?</div>
                <p class="test-subtitle">Пройдите тест из 8 вопросов, чтобы мы подобрали идеальное направление обучения</p>

                <div class="test-progress">
                    <div class="progress-info">
                        <span>Прогресс</span>
                        <span><span class="current-question">1</span>/8</span>
                    </div>
                    <div class="progress-bar-test">
                        <div class="progress-fill-test" style="width: 12.5%"></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('course-recommendation.store') }}" method="POST" id="course-test-form">
                @csrf

                <div class="test-questions">
                    <!-- Вопрос 1 -->
                    <div class="question-card" data-question="1">
                        <div class="question-header">
                            <div class="question-number">Вопрос 1</div>
                            <div class="question-level">Цели</div>
                        </div>

                        <div class="question-text">Какова ваша основная цель изучения английского?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="business">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Карьерный рост и работа в международной компании</div>
                                </div>
                                <input type="radio" name="answers[goal]" value="business" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="it">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Работа в IT и технологиях</div>
                                </div>
                                <input type="radio" name="answers[goal]" value="it" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="academic">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Учеба за границей или подготовка к экзаменам</div>
                                </div>
                                <input type="radio" name="answers[goal]" value="academic" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="daily">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Путешествия и повседневное общение</div>
                                </div>
                                <input type="radio" name="answers[goal]" value="daily" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 2 -->
                    <div class="question-card" data-question="2">
                        <div class="question-header">
                            <div class="question-number">Вопрос 2</div>
                            <div class="question-level">Сфера деятельности</div>
                        </div>

                        <div class="question-text">В какой сфере вы работаете или планируете работать?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="business_corp">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Бизнес, менеджмент, финансы</div>
                                </div>
                                <input type="radio" name="answers[field]" value="business_corp" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="it_tech">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">IT, технологии, разработка</div>
                                </div>
                                <input type="radio" name="answers[field]" value="it_tech" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="education">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Образование, наука, исследования</div>
                                </div>
                                <input type="radio" name="answers[field]" value="education" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="other">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Другая сфера или не работаю</div>
                                </div>
                                <input type="radio" name="answers[field]" value="other" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 3 -->
                    <div class="question-card" data-question="3">
                        <div class="question-header">
                            <div class="question-number">Вопрос 3</div>
                            <div class="question-level">Навыки</div>
                        </div>

                        <div class="question-text">Какие навыки английского вам наиболее важны?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="presentations">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Презентации, переговоры, деловая переписка</div>
                                </div>
                                <input type="radio" name="answers[skills]" value="presentations" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="technical">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Техническая документация, общение с коллегами</div>
                                </div>
                                <input type="radio" name="answers[skills]" value="technical" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="academic_writing">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Академическое письмо, исследовательские работы</div>
                                </div>
                                <input type="radio" name="answers[skills]" value="academic_writing" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="conversation">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Разговорная речь, понимание на слух</div>
                                </div>
                                <input type="radio" name="answers[skills]" value="conversation" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 4 -->
                    <div class="question-card" data-question="4">
                        <div class="question-header">
                            <div class="question-number">Вопрос 4</div>
                            <div class="question-level">Время</div>
                        </div>

                        <div class="question-text">Сколько времени в неделю вы готовы уделять обучению?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="intensive">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">5-7 часов - хочу максимально быстрый результат</div>
                                </div>
                                <input type="radio" name="answers[time]" value="intensive" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="medium">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">3-4 часа - регулярные занятия</div>
                                </div>
                                <input type="radio" name="answers[time]" value="medium" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="light">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">1-2 часа - для поддержания уровня</div>
                                </div>
                                <input type="radio" name="answers[time]" value="light" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 5 -->
                    <div class="question-card" data-question="5">
                        <div class="question-header">
                            <div class="question-number">Вопрос 5</div>
                            <div class="question-level">Темы</div>
                        </div>

                        <div class="question-text">Какие темы вас больше интересуют?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="business_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Маркетинг, финансы, управление проектами</div>
                                </div>
                                <input type="radio" name="answers[topics]" value="business_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="tech_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Технологии, программирование, инновации</div>
                                </div>
                                <input type="radio" name="answers[topics]" value="tech_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="academic_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Наука, культура, искусство</div>
                                </div>
                                <input type="radio" name="answers[topics]" value="academic_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="daily_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Путешествия, хобби, повседневная жизнь</div>
                                </div>
                                <input type="radio" name="answers[topics]" value="daily_topics" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 6 -->
                    <div class="question-card" data-question="6">
                        <div class="question-header">
                            <div class="question-number">Вопрос 6</div>
                            <div class="question-level">Сроки</div>
                        </div>

                        <div class="question-text">Когда вам нужно достичь результата?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="urgent">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">В течение 1-3 месяцев (срочно)</div>
                                </div>
                                <input type="radio" name="answers[deadline]" value="urgent" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="medium_term">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">3-6 месяцев (планирую заранее)</div>
                                </div>
                                <input type="radio" name="answers[deadline]" value="medium_term" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="long_term">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">6+ месяцев (для себя, без спешки)</div>
                                </div>
                                <input type="radio" name="answers[deadline]" value="long_term" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 7 -->
                    <div class="question-card" data-question="7">
                        <div class="question-header">
                            <div class="question-number">Вопрос 7</div>
                            <div class="question-level">Формат</div>
                        </div>

                        <div class="question-text">Какой формат обучения вам больше подходит?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="individual">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Индивидуальные занятия с преподавателем</div>
                                </div>
                                <input type="radio" name="answers[format]" value="individual" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="group">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Групповые занятия</div>
                                </div>
                                <input type="radio" name="answers[format]" value="group" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="self_study">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Самостоятельное обучение + консультации</div>
                                </div>
                                <input type="radio" name="answers[format]" value="self_study" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 8 -->
                    <div class="question-card" data-question="8">
                        <div class="question-header">
                            <div class="question-number">Вопрос 8</div>
                            <div class="question-level">Бюджет</div>
                        </div>

                        <div class="question-text">Какой бюджет вы рассматриваете для обучения?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="premium">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Премиум - индивидуальный подход и быстрый результат</div>
                                </div>
                                <input type="radio" name="answers[budget]" value="premium" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="standard">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Стандарт - сбалансированное соотношение цены и качества</div>
                                </div>
                                <input type="radio" name="answers[budget]" value="standard" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="economy">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Эконом - доступное обучение с базовым функционалом</div>
                                </div>
                                <input type="radio" name="answers[budget]" value="economy" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="test-actions">
                    <button type="button" class="btn-prev" style="display: none;">
                        <i class="fas fa-arrow-left"></i>
                        Предыдущий вопрос
                    </button>

                    <button type="button" class="btn-next">
                        Следующий вопрос
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <button type="submit" class="btn-submit" style="display: none;">
                        <i class="fas fa-paper-plane"></i>
                        Узнать рекомендацию
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = document.querySelectorAll('.question-card');
            const prevBtn = document.querySelector('.btn-prev');
            const nextBtn = document.querySelector('.btn-next');
            const submitBtn = document.querySelector('.btn-submit');
            const progressFill = document.querySelector('.progress-fill-test');
            const currentQuestionSpan = document.querySelector('.current-question');

            let currentQuestion = 1;
            const totalQuestions = questions.length;

            function showQuestion(index) {
                questions.forEach((q, i) => {
                    q.style.display = i === index - 1 ? 'block' : 'none';
                    if (i === index - 1) {
                        q.classList.add('active');
                    } else {
                        q.classList.remove('active');
                    }
                });

                currentQuestionSpan.textContent = index;
                progressFill.style.width = `${(index / totalQuestions) * 100}%`;

                // Управление видимостью кнопок
                prevBtn.style.display = index > 1 ? 'flex' : 'none';
                nextBtn.style.display = index < totalQuestions ? 'flex' : 'none';
                submitBtn.style.display = index === totalQuestions ? 'flex' : 'none';
            }

            // Обработчики для вариантов ответов
            document.querySelectorAll('.option-card').forEach(card => {
                card.addEventListener('click', function() {
                    const questionCard = this.closest('.question-card');
                    questionCard.querySelectorAll('.option-card').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');

                    const input = this.querySelector('input[type="radio"]');
                    input.checked = true;
                });
            });

            // Навигация
            nextBtn.addEventListener('click', function() {
                // Проверяем, выбран ли ответ на текущем вопросе
                const currentQuestionCard = questions[currentQuestion - 1];
                const selectedOption = currentQuestionCard.querySelector('.option-card.selected');

                if (!selectedOption) {
                    alert('Пожалуйста, выберите вариант ответа');
                    return;
                }

                if (currentQuestion < totalQuestions) {
                    currentQuestion++;
                    showQuestion(currentQuestion);
                }
            });

            prevBtn.addEventListener('click', function() {
                if (currentQuestion > 1) {
                    currentQuestion--;
                    showQuestion(currentQuestion);
                }
            });

            // Валидация формы
            const form = document.getElementById('course-test-form');
            form.addEventListener('submit', function(e) {
                const answeredQuestions = document.querySelectorAll('.placement-test-option-input:checked').length;

                if (answeredQuestions < totalQuestions) {
                    e.preventDefault();
                    alert('Пожалуйста, ответьте на все вопросы перед отправкой');
                    return;
                }
            });

            // Инициализация
            showQuestion(1);
        });
    </script>
@endsection
