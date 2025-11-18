@extends('app')

@section('title', 'Бесплатное пробное занятие - Knowly')

@section('content')
    <div class="free-lesson-container">
        <div class="free-lesson-hero">
            <div class="container">
                <div class="free-lesson-card">
                    <div class="free-lesson-header">
                        <div class="free-lesson-badge">
                            <i class="fas fa-star"></i>
                            Бесплатный урок
                        </div>
                        <h1>Ваш первый урок английского</h1>
                        <p class="free-lesson-subtitle">Начните говорить на английском уже через 60 минут</p>

                        <div class="lesson-progress">
                            <div class="progress-steps" data-current-step="1" data-total-steps="5">
                                <div class="step active" data-step="1">
                                    <div class="step-number">1</div>
                                    <span class="step-label">Знакомство</span>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="step-number">2</div>
                                    <span class="step-label">Алфавит</span>
                                </div>
                                <div class="step" data-step="3">
                                    <div class="step-number">3</div>
                                    <span class="step-label">Числа</span>
                                </div>
                                <div class="step" data-step="4">
                                    <div class="step-number">4</div>
                                    <span class="step-label">Диалог</span>
                                </div>
                                <div class="step" data-step="5">
                                    <div class="step-number">5</div>
                                    <span class="step-label">Результаты</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="free-lesson-content">
                        <!-- Блок 1: Знакомство -->
                        <div class="lesson-block active" id="block-1">
                            <div class="block-header">
                                <h2><i class="fas fa-handshake"></i> Давайте познакомимся!</h2>
                                <div class="time-indicator">10 минут</div>
                            </div>

                            <div class="phrases-grid">
                                <div class="phrase-card">
                                    <div class="phrase-text">Hello! / Hi!</div>
                                    <div class="phrase-translation">Привет!</div>
                                    <button class="btn-audio" data-audio="hello">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                </div>

                                <div class="phrase-card">
                                    <div class="phrase-text">What's your name?</div>
                                    <div class="phrase-translation">Как тебя зовут?</div>
                                    <button class="btn-audio" data-audio="whats-name">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                </div>

                                <div class="phrase-card">
                                    <div class="phrase-text">My name is...</div>
                                    <div class="phrase-translation">Меня зовут...</div>
                                    <button class="btn-audio" data-audio="my-name">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                </div>

                                <div class="phrase-card">
                                    <div class="phrase-text">Nice to meet you!</div>
                                    <div class="phrase-translation">Приятно познакомиться!</div>
                                    <button class="btn-audio" data-audio="nice-meet">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="interactive-exercise">
                                <h3>Попрактикуйтесь:</h3>
                                <div class="exercise-content">
                                    <p>Напишите, как представились бы вы:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-input" placeholder="My name is..." id="self-intro">
                                        <button class="btn-check" onclick="checkIntroduction()">
                                            <i class="fas fa-check"></i> Проверить
                                        </button>
                                    </div>
                                    <div class="exercise-feedback" id="intro-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Блок 2: Алфавит -->
                        <div class="lesson-block" id="block-2">
                            <div class="block-header">
                                <h2><i class="fas fa-font"></i> Английский алфавит</h2>
                                <div class="time-indicator">15 минут</div>
                            </div>

                            <div class="alphabet-grid">
                                <div class="letter-card" data-letter="A">
                                    <div class="letter">A</div>
                                    <div class="word">Apple</div>
                                    <div class="translation">Яблоко</div>
                                </div>
                                <div class="letter-card" data-letter="B">
                                    <div class="letter">B</div>
                                    <div class="word">Boy</div>
                                    <div class="translation">Мальчик</div>
                                </div>
                                <div class="letter-card" data-letter="C">
                                    <div class="letter">C</div>
                                    <div class="word">Cat</div>
                                    <div class="translation">Кот</div>
                                </div>
                                <div class="letter-card" data-letter="D">
                                    <div class="letter">D</div>
                                    <div class="word">Dog</div>
                                    <div class="translation">Собака</div>
                                </div>
                                <div class="letter-card" data-letter="E">
                                    <div class="letter">E</div>
                                    <div class="word">Egg</div>
                                    <div class="translation">Яйцо</div>
                                </div>
                            </div>

                            <div class="audio-exercise">
                                <h3>Проверка слуха:</h3>
                                <p>Прослушайте и выберите правильную букву:</p>
                                <div class="audio-quiz">
                                    <button class="btn-play-audio" onclick="playLetterQuiz()">
                                        <i class="fas fa-play"></i> Воспроизвести
                                    </button>
                                    <div class="quiz-options">
                                        <button class="quiz-option" onclick="checkLetterAnswer('A')">A</button>
                                        <button class="quiz-option" onclick="checkLetterAnswer('B')">B</button>
                                        <button class="quiz-option" onclick="checkLetterAnswer('C')">C</button>
                                        <button class="quiz-option" onclick="checkLetterAnswer('D')">D</button>
                                    </div>
                                    <div class="quiz-feedback" id="letter-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Блок 3: Числа -->
                        <div class="lesson-block" id="block-3">
                            <div class="block-header">
                                <h2><i class="fas fa-sort-numeric-up"></i> Числа 1-10</h2>
                                <div class="time-indicator">10 минут</div>
                            </div>

                            <div class="numbers-grid">
                                @for($i = 1; $i <= 10; $i++)
                                    @php
                                        $numbers = [
                                            1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
                                            6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten'
                                        ];
                                    @endphp
                                    <div class="number-card" data-number="{{ $i }}">
                                        <div class="number">{{ $i }}</div>
                                        <div class="word">{{ $numbers[$i] }}</div>
                                        <button class="btn-audio" data-audio="number-{{ $i }}">
                                            <i class="fas fa-volume-up"></i>
                                        </button>
                                    </div>
                                @endfor
                            </div>

                            <div class="counting-exercise">
                                <h3>Посчитайте предметы:</h3>
                                <div class="counting-items">
                                    <div class="count-item">
                                        <span class="item-emoji">🐱</span>
                                        <span class="item-count">3 cats</span>
                                    </div>
                                    <div class="count-item">
                                        <span class="item-emoji">📚</span>
                                        <span class="item-count">5 books</span>
                                    </div>
                                    <div class="count-item">
                                        <span class="item-emoji">✏️</span>
                                        <span class="item-count">7 pencils</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Блок 4: Диалог -->
                        <div class="lesson-block" id="block-4">
                            <div class="block-header">
                                <h2><i class="fas fa-comments"></i> Ваш первый диалог</h2>
                                <div class="time-indicator">10 минут</div>
                            </div>

                            <div class="dialog-container">
                                <div class="dialog-line teacher">
                                    <div class="avatar">T</div>
                                    <div class="message">Hello!</div>
                                </div>
                                <div class="dialog-line student">
                                    <div class="avatar">S</div>
                                    <div class="message">Hi!</div>
                                </div>
                                <div class="dialog-line teacher">
                                    <div class="avatar">T</div>
                                    <div class="message">What's your name?</div>
                                </div>
                                <div class="dialog-line student editable">
                                    <div class="avatar">S</div>
                                    <div class="message">
                                        <input type="text" class="dialog-input" placeholder="My name is..." id="student-name">
                                    </div>
                                </div>
                                <div class="dialog-line teacher">
                                    <div class="avatar">T</div>
                                    <div class="message">Nice to meet you, <span id="name-placeholder">[ваше имя]</span>!</div>
                                </div>
                                <div class="dialog-line student">
                                    <div class="avatar">S</div>
                                    <div class="message">Nice to meet you too!</div>
                                </div>
                            </div>

                            <div class="practice-buttons">
                                <button class="btn-practice" onclick="practiceDialog()">
                                    <i class="fas fa-play-circle"></i> Прорепетировать диалог
                                </button>
                                <button class="btn-record" onclick="startRecording()">
                                    <i class="fas fa-microphone"></i> Записать свой ответ
                                </button>
                            </div>
                        </div>

                        <!-- Блок 5: Результаты -->
                        <div class="lesson-block" id="block-5">
                            <div class="completion-card">
                                <div class="completion-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h2>Поздравляем! 🎉</h2>
                                <p>Вы завершили пробный урок и научились:</p>

                                <div class="achievements-list">
                                    <div class="achievement">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Представляться на английском</span>
                                    </div>
                                    <div class="achievement">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Знать 5 букв алфавита</span>
                                    </div>
                                    <div class="achievement">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Считать до 10</span>
                                    </div>
                                    <div class="achievement">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Вести простой диалог</span>
                                    </div>
                                </div>

                                <div class="completion-actions">
                                    <a href="/courses" class="btn btn-primary">
                                        <i class="fas fa-rocket"></i>
                                        Продолжить обучение
                                    </a>
                                    <a href="/dashboard" class="btn btn-outline">
                                        <i class="fas fa-download"></i>
                                        Скачать материалы урока
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lesson-navigation">
                        <button class="btn-prev" onclick="prevBlock()" disabled>
                            <i class="fas fa-arrow-left"></i> Назад
                        </button>
                        <div class="progress-indicator">
                            <span class="current-block">1</span> из <span class="total-blocks">5</span>
                        </div>
                        <button class="btn-next" onclick="nextBlock()">
                            Далее <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentBlock = 1;
        const totalBlocks = 5;

        function updateNavigation() {
            // Показать/скрыть блоки
            document.querySelectorAll('.lesson-block').forEach((block, index) => {
                block.classList.toggle('active', index + 1 === currentBlock);
            });

            // Обновить кнопки навигации
            document.querySelector('.btn-prev').disabled = currentBlock === 1;

            if (currentBlock === totalBlocks) {
                document.querySelector('.btn-next').style.display = 'none';
            } else {
                document.querySelector('.btn-next').style.display = 'flex';
            }

            // Обновить индикатор прогресса
            document.querySelector('.current-block').textContent = currentBlock;
        }

        function updateProgress() {
            const progressElement = document.querySelector('.progress-steps');
            progressElement.setAttribute('data-current-step', currentBlock);

            // Обновляем классы шагов
            document.querySelectorAll('.step').forEach((step, index) => {
                const stepNumber = index + 1;

                step.classList.remove('active', 'completed');

                if (stepNumber === currentBlock) {
                    step.classList.add('active');
                } else if (stepNumber < currentBlock) {
                    step.classList.add('completed');
                }
            });
        }

        function nextBlock() {
            if (currentBlock < totalBlocks) {
                currentBlock++;
                updateNavigation();
                updateProgress();
                window.scrollTo({ top: document.querySelector('.free-lesson-content').offsetTop - 100, behavior: 'smooth' });
            }
        }

        function prevBlock() {
            if (currentBlock > 1) {
                currentBlock--;
                updateNavigation();
                updateProgress();
                window.scrollTo({ top: document.querySelector('.free-lesson-content').offsetTop - 100, behavior: 'smooth' });
            }
        }

        // Аудио функции
        function playAudio(audioId) {
            // В реальном приложении здесь будет воспроизведение аудио
            console.log('Playing audio:', audioId);

            // Имитация воспроизведения
            const btn = event.target.closest('.btn-audio');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-volume-up"></i>';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-volume-up"></i>';
                }, 1000);
            }
        }

        // Проверка введенного представления
        function checkIntroduction() {
            const input = document.getElementById('self-intro');
            const feedback = document.getElementById('intro-feedback');

            if (input.value.trim().toLowerCase().includes('my name is')) {
                feedback.textContent = 'Отлично! Вы правильно представились.';
                feedback.className = 'exercise-feedback success';
            } else {
                feedback.textContent = 'Попробуйте начать с "My name is..."';
                feedback.className = 'exercise-feedback';
            }
        }

        // Викторина с буквами
        function playLetterQuiz() {
            // В реальном приложении здесь будет воспроизведение аудио с буквой
            const letters = ['A', 'B', 'C', 'D'];
            const randomLetter = letters[Math.floor(Math.random() * letters.length)];

            // Сохраняем правильный ответ для проверки
            window.currentQuizLetter = randomLetter;

            console.log('Playing letter:', randomLetter);
        }

        function checkLetterAnswer(letter) {
            const feedback = document.getElementById('letter-feedback');

            if (letter === window.currentQuizLetter) {
                feedback.textContent = 'Правильно! Вы отлично слышите.';
                feedback.className = 'exercise-feedback success';
            } else {
                feedback.textContent = 'Попробуйте еще раз!';
                feedback.className = 'exercise-feedback';
            }
        }

        // Обновление имени в диалоге
        document.getElementById('student-name').addEventListener('input', function(e) {
            document.getElementById('name-placeholder').textContent = e.target.value || '[ваше имя]';
        });

        // Репетиция диалога
        function practiceDialog() {
            const dialogLines = document.querySelectorAll('.dialog-line');
            let delay = 0;

            dialogLines.forEach((line, index) => {
                setTimeout(() => {
                    line.style.opacity = '0.3';
                    setTimeout(() => {
                        line.style.opacity = '1';
                    }, 1000);
                }, delay);
                delay += 2000;
            });
        }

        // Запись аудио (заглушка)
        function startRecording() {
            alert('В полной версии приложения здесь будет запись вашего голоса!');
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', function() {
            updateNavigation();
            updateProgress();

            // Добавляем обработчики для аудио кнопок
            document.querySelectorAll('.btn-audio').forEach(btn => {
                btn.addEventListener('click', function() {
                    const audioId = this.getAttribute('data-audio');
                    playAudio(audioId);
                });
            });

            // Добавляем обработчики для карточек букв
            document.querySelectorAll('.letter-card').forEach(card => {
                card.addEventListener('click', function() {
                    const letter = this.getAttribute('data-letter');
                    playAudio(`letter-${letter}`);
                });
            });
        });
    </script>
@endsection
