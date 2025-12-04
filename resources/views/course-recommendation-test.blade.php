@extends('app')

@section('title', 'Тест на подбор курса - Knowly')

@section('content')
    <div class="placement-test-container">
        <div class="profile-card placement-test-card">
            <div class="placement-test-header">
                <div class="test-title">Какой английский вам подходит?</div>
                <p class="test-subtitle">Пройдите тест из 15 вопросов, чтобы мы подобрали идеальное направление обучения</p>

                <div class="test-progress">
                    <div class="progress-info">
                        <span>Прогресс</span>
                        <span><span class="current-question">1</span>/15</span>
                    </div>
                    <div class="progress-bar-test">
                        <div class="progress-fill-test" style="width: 6.7%"></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('course-recommendation.store') }}" method="POST" id="course-test-form">
                @csrf

                <div class="test-questions">
                    <!-- Вопрос 1 -->
                    <div class="question-card" data-question="1" data-multiple="false">
                        <div class="question-header">
                            <div class="question-number">Вопрос 1</div>
                            <div class="question-level">Основная цель</div>
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
                    <div class="question-card" data-question="2" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 2</div>
                            <div class="question-level">Сферы деятельности</div>
                        </div>

                        <div class="question-text">В каких сферах вы работаете или планируете работать? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="business_corp">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Бизнес, менеджмент, финансы</div>
                                </div>
                                <input type="checkbox" name="answers[fields][]" value="business_corp" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="it_tech">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">IT, технологии, разработка</div>
                                </div>
                                <input type="checkbox" name="answers[fields][]" value="it_tech" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="education">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Образование, наука, исследования</div>
                                </div>
                                <input type="checkbox" name="answers[fields][]" value="education" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="healthcare">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Медицина, фармацевтика</div>
                                </div>
                                <input type="checkbox" name="answers[fields][]" value="healthcare" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="creative">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Творчество, дизайн, искусство</div>
                                </div>
                                <input type="checkbox" name="answers[fields][]" value="creative" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="other_field">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Другая сфера или не работаю</div>
                                </div>
                                <input type="checkbox" name="answers[fields][]" value="other_field" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 3 -->
                    <div class="question-card" data-question="3" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 3</div>
                            <div class="question-level">Навыки</div>
                        </div>

                        <div class="question-text">Какие навыки английского вам наиболее важны? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="presentations">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Презентации, публичные выступления</div>
                                </div>
                                <input type="checkbox" name="answers[skills][]" value="presentations" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="negotiations">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Переговоры, деловые встречи</div>
                                </div>
                                <input type="checkbox" name="answers[skills][]" value="negotiations" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="technical">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Техническая документация, спецификации</div>
                                </div>
                                <input type="checkbox" name="answers[skills][]" value="technical" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="academic_writing">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Академическое письмо, исследовательские работы</div>
                                </div>
                                <input type="checkbox" name="answers[skills][]" value="academic_writing" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="conversation">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Разговорная речь, понимание на слух</div>
                                </div>
                                <input type="checkbox" name="answers[skills][]" value="conversation" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="writing">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Деловая переписка, email-коммуникация</div>
                                </div>
                                <input type="checkbox" name="answers[skills][]" value="writing" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 4 -->
                    <div class="question-card" data-question="4" data-multiple="false">
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
                    <div class="question-card" data-question="5" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 5</div>
                            <div class="question-level">Темы</div>
                        </div>

                        <div class="question-text">Какие темы вас больше интересуют? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="business_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Маркетинг, финансы, управление проектами</div>
                                </div>
                                <input type="checkbox" name="answers[topics][]" value="business_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="tech_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Технологии, программирование, инновации</div>
                                </div>
                                <input type="checkbox" name="answers[topics][]" value="tech_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="academic_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Наука, культура, искусство, литература</div>
                                </div>
                                <input type="checkbox" name="answers[topics][]" value="academic_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="daily_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Путешествия, хобби, повседневная жизнь</div>
                                </div>
                                <input type="checkbox" name="answers[topics][]" value="daily_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="medical_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Медицина, здоровье, фармацевтика</div>
                                </div>
                                <input type="checkbox" name="answers[topics][]" value="medical_topics" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="creative_topics">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Дизайн, архитектура, творческие профессии</div>
                                </div>
                                <input type="checkbox" name="answers[topics][]" value="creative_topics" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 6 -->
                    <div class="question-card" data-question="6" data-multiple="false">
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
                    <div class="question-card" data-question="7" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 7</div>
                            <div class="question-level">Формат</div>
                        </div>

                        <div class="question-text">Какие форматы обучения вам подходят? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="individual">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Индивидуальные занятия с преподавателем</div>
                                </div>
                                <input type="checkbox" name="answers[formats][]" value="individual" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="group">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Групповые занятия</div>
                                </div>
                                <input type="checkbox" name="answers[formats][]" value="group" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="self_study">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Самостоятельное обучение + консультации</div>
                                </div>
                                <input type="checkbox" name="answers[formats][]" value="self_study" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="corporate">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Корпоративное обучение для сотрудников</div>
                                </div>
                                <input type="checkbox" name="answers[formats][]" value="corporate" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 8 -->
                    <div class="question-card" data-question="8" data-multiple="false">
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

                    <!-- Вопрос 9 -->
                    <div class="question-card" data-question="9" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 9</div>
                            <div class="question-level">Стиль обучения</div>
                        </div>

                        <div class="question-text">Какой стиль обучения вам ближе? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="structured">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Структурированные уроки по учебнику</div>
                                </div>
                                <input type="checkbox" name="answers[learning_style][]" value="structured" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="conversational">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Разговорная практика, дискуссии</div>
                                </div>
                                <input type="checkbox" name="answers[learning_style][]" value="conversational" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="project_based">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Проектная работа, реальные кейсы</div>
                                </div>
                                <input type="checkbox" name="answers[learning_style][]" value="project_based" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="multimedia">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Видео, аудио, интерактивные материалы</div>
                                </div>
                                <input type="checkbox" name="answers[learning_style][]" value="multimedia" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 10 -->
                    <div class="question-card" data-question="10" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 10</div>
                            <div class="question-level">Дополнительные потребности</div>
                        </div>

                        <div class="question-text">Какие дополнительные потребности у вас есть? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="certification">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Сертификация, официальный документ</div>
                                </div>
                                <input type="checkbox" name="answers[needs][]" value="certification" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="career_support">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Карьерная поддержка, подготовка к собеседованиям</div>
                                </div>
                                <input type="checkbox" name="answers[needs][]" value="career_support" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="flexible_schedule">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Гибкий график, возможность переносить занятия</div>
                                </div>
                                <input type="checkbox" name="answers[needs][]" value="flexible_schedule" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="native_speakers">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Занятия с носителями языка</div>
                                </div>
                                <input type="checkbox" name="answers[needs][]" value="native_speakers" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 11 -->
                    <div class="question-card" data-question="11" data-multiple="false">
                        <div class="question-header">
                            <div class="question-number">Вопрос 11</div>
                            <div class="question-level">Текущий уровень</div>
                        </div>

                        <div class="question-text">Как вы оцениваете свой текущий уровень английского?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="beginner">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Начинающий (знаю алфавит, простые фразы)</div>
                                </div>
                                <input type="radio" name="answers[current_level]" value="beginner" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="elementary">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Элементарный (могу поддержать простой диалог)</div>
                                </div>
                                <input type="radio" name="answers[current_level]" value="elementary" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="intermediate">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Средний (свободно общаюсь на бытовые темы)</div>
                                </div>
                                <input type="radio" name="answers[current_level]" value="intermediate" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="upper_intermediate">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Выше среднего (могу работать с профессиональными текстами)</div>
                                </div>
                                <input type="radio" name="answers[current_level]" value="upper_intermediate" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="advanced">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Продвинутый (свободно владею языком, хочу совершенствоваться)</div>
                                </div>
                                <input type="radio" name="answers[current_level]" value="advanced" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 12 -->
                    <div class="question-card" data-question="12" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 12</div>
                            <div class="question-level">Приоритеты</div>
                        </div>

                        <div class="question-text">Что для вас наиболее важно в обучении? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="speed">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Скорость достижения результата</div>
                                </div>
                                <input type="checkbox" name="answers[priorities][]" value="speed" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="quality">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Качество и глубина знаний</div>
                                </div>
                                <input type="checkbox" name="answers[priorities][]" value="quality" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="price">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Доступная стоимость</div>
                                </div>
                                <input type="checkbox" name="answers[priorities][]" value="price" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="flexibility">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Гибкость и удобство расписания</div>
                                </div>
                                <input type="checkbox" name="answers[priorities][]" value="flexibility" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 13 -->
                    <div class="question-card" data-question="13" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 13</div>
                            <div class="question-level">Специализация</div>
                        </div>

                        <div class="question-text">В каких конкретных областях вам нужен английский? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="meetings">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Участие в совещаниях и конференциях</div>
                                </div>
                                <input type="checkbox" name="answers[specializations][]" value="meetings" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="emails">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Деловая переписка и отчетность</div>
                                </div>
                                <input type="checkbox" name="answers[specializations][]" value="emails" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="programming">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Чтение технической документации и кода</div>
                                </div>
                                <input type="checkbox" name="answers[specializations][]" value="programming" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="academic_research">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Академические исследования и публикации</div>
                                </div>
                                <input type="checkbox" name="answers[specializations][]" value="academic_research" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="customer_service">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Общение с клиентами и партнерами</div>
                                </div>
                                <input type="checkbox" name="answers[specializations][]" value="customer_service" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 14 -->
                    <div class="question-card" data-question="14" data-multiple="false">
                        <div class="question-header">
                            <div class="question-number">Вопрос 14</div>
                            <div class="question-level">Мотивация</div>
                        </div>

                        <div class="question-text">Что вас мотивирует изучать английский прямо сейчас?</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="career_opportunity">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Конкретная карьерная возможность или проект</div>
                                </div>
                                <input type="radio" name="answers[motivation]" value="career_opportunity" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="personal_development">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Личностное развитие и самообразование</div>
                                </div>
                                <input type="radio" name="answers[motivation]" value="personal_development" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="travel">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Предстоящая поездка или переезд</div>
                                </div>
                                <input type="radio" name="answers[motivation]" value="travel" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="requirement">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Требования работодателя или учебного заведения</div>
                                </div>
                                <input type="radio" name="answers[motivation]" value="requirement" class="placement-test-option-input" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Вопрос 15 -->
                    <div class="question-card" data-question="15" data-multiple="true">
                        <div class="question-header">
                            <div class="question-number">Вопрос 15</div>
                            <div class="question-level">Ожидания</div>
                        </div>

                        <div class="question-text">Что вы ожидаете от курса английского? (можно выбрать несколько)</div>

                        <div class="options-grid">
                            <div class="option-card" data-option="fluency">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Свободное владение языком</div>
                                </div>
                                <input type="checkbox" name="answers[expectations][]" value="fluency" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="specific_skills">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Конкретные профессиональные навыки</div>
                                </div>
                                <input type="checkbox" name="answers[expectations][]" value="specific_skills" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="confidence">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Уверенность в общении</div>
                                </div>
                                <input type="checkbox" name="answers[expectations][]" value="confidence" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="certificate">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Официальный сертификат</div>
                                </div>
                                <input type="checkbox" name="answers[expectations][]" value="certificate" class="placement-test-option-input" style="display: none;">
                            </div>

                            <div class="option-card" data-option="networking">
                                <div class="option-content">
                                    <div class="option-marker"></div>
                                    <div class="option-text">Нетворкинг и новые знакомства</div>
                                </div>
                                <input type="checkbox" name="answers[expectations][]" value="networking" class="placement-test-option-input" style="display: none;">
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
                    const isMultiple = questionCard.getAttribute('data-multiple') === 'true';

                    if (!isMultiple) {
                        // Одиночный выбор - снимаем выделение с других
                        questionCard.querySelectorAll('.option-card').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                    }

                    // Переключаем выделение текущего варианта
                    this.classList.toggle('selected');

                    const input = this.querySelector('input');
                    if (isMultiple) {
                        input.checked = !input.checked;
                    } else {
                        input.checked = true;
                    }
                });
            });

            // Навигация
            nextBtn.addEventListener('click', function() {
                // Проверяем, выбран ли ответ на текущем вопросе
                const currentQuestionCard = questions[currentQuestion - 1];
                const isMultiple = currentQuestionCard.getAttribute('data-multiple') === 'true';
                const selectedOptions = currentQuestionCard.querySelectorAll('.option-card.selected');

                if (selectedOptions.length === 0) {
                    alert('Пожалуйста, выберите хотя бы один вариант ответа');
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
                let allAnswered = true;

                questions.forEach((question, index) => {
                    const isMultiple = question.getAttribute('data-multiple') === 'true';
                    const selectedOptions = question.querySelectorAll('.option-card.selected');

                    if (selectedOptions.length === 0) {
                        allAnswered = false;
                        // Показываем вопрос, который не был отвечен
                        showQuestion(index + 1);
                        alert('Пожалуйста, ответьте на все вопросы перед отправкой');
                        e.preventDefault();
                        return;
                    }
                });

                if (!allAnswered) {
                    e.preventDefault();
                }
            });

            // Инициализация
            showQuestion(1);
        });
    </script>
@endsection
