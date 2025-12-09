@extends('app')

@section('title', $test->title . ' - Knowly')

@section('content')
    <div class="test-taking-container">
        <div class="test-header">
            <div class="test-info">
                <h1>{{ $test->title }}</h1>
                <p class="test-subtitle">{{ $test->description }}</p>

                <div class="test-meta">
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>Попытка #{{ $testResult->attempt_number }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Начато: {{ $testResult->started_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($test->course)
                        <div class="meta-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $test->course->title }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="test-timer">
                @if($test->time_limit)
                    <div class="timer-box">
                        <div class="timer-label">Осталось времени</div>
                        <div class="timer-display" id="timer">
                            {{ str_pad(floor($test->time_limit), 2, '0', STR_PAD_LEFT) }}:00
                        </div>
                    </div>
                @else
                    <div class="timer-box">
                        <div class="timer-label">Без ограничения времени</div>
                        <div class="timer-display">∞</div>
                    </div>
                @endif

                <div class="questions-counter">
                    <span id="current-question">1</span>/<span>{{ $test->questions->count() }}</span>
                </div>
            </div>
        </div>

        <div class="test-progress">
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
            </div>
            <div class="progress-label">
                <span>Прогресс: <span id="progress-text">0</span>%</span>
            </div>
        </div>

        <form id="test-form" action="{{ route('universal-tests.submit', $test->id) }}" method="POST">
            @csrf

            <div class="questions-container">
                @foreach($test->questions as $index => $question)
                    @php
                        $questionId = $question->id;
                        $questionType = $question->type;
                    @endphp

                    <div class="question-card" id="question-{{ $questionId }}"
                         style="{{ $index === 0 ? '' : 'display: none;' }}"
                         data-question-index="{{ $index }}">

                        <div class="question-header">
                            <h2>Вопрос {{ $index + 1 }}</h2>
                            @if($question->points)
                                <div class="question-points">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $question->points }} баллов</span>
                                </div>
                            @endif
                        </div>

                        <div class="question-content">
                            <p class="question-text">{{ $question->question }}</p>

                            @if($questionType === 'multiple_choice')
                                <div class="options-container">
                                    @foreach($question->options as $optionIndex => $option)
                                        <label class="option-label">
                                            <input type="radio"
                                                   name="answers[{{ $questionId }}]"
                                                   value="{{ $optionIndex }}"
                                                   class="option-input"
                                                   onchange="saveAnswer({{ $questionId }}, {{ $optionIndex }})">
                                            <div class="option-content">
                                                <div class="option-letter">
                                                    {{ chr(65 + $optionIndex) }}
                                                </div>
                                                <div class="option-text">
                                                    {{ $option }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($questionType === 'true_false')
                                <div class="options-container">
                                    <label class="option-label">
                                        <input type="radio"
                                               name="answers[{{ $questionId }}]"
                                               value="true"
                                               class="option-input"
                                               onchange="saveAnswer({{ $questionId }}, 'true')">
                                        <div class="option-content">
                                            <div class="option-letter">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="option-text">
                                                True
                                            </div>
                                        </div>
                                    </label>

                                    <label class="option-label">
                                        <input type="radio"
                                               name="answers[{{ $questionId }}]"
                                               value="false"
                                               class="option-input"
                                               onchange="saveAnswer({{ $questionId }}, 'false')">
                                        <div class="option-content">
                                            <div class="option-letter">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="option-text">
                                                False
                                            </div>
                                        </div>
                                    </label>
                                </div>

                            @elseif($questionType === 'short_answer')
                                <div class="short-answer-container">
                                    <input type="text"
                                           name="answers[{{ $questionId }}]"
                                           class="short-answer-input"
                                           placeholder="Введите ваш ответ..."
                                           oninput="saveAnswer({{ $questionId }}, this.value)">
                                </div>
                            @endif
                        </div>

                        <div class="question-navigation">
                            @if($index > 0)
                                <button type="button" class="btn btn-outline" onclick="prevQuestion()">
                                    <i class="fas fa-arrow-left"></i> Назад
                                </button>
                            @else
                                <div></div>
                            @endif

                            <div class="nav-status">
                                <span id="answered-count-{{ $questionId }}">0</span> из {{ $questionType === 'multiple_choice' || $questionType === 'true_false' ? 1 : 'ответ' }}
                            </div>

                            @if($index < $test->questions->count() - 1)
                                <button type="button" class="btn btn-primary" onclick="nextQuestion()">
                                    Далее <i class="fas fa-arrow-right"></i>
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary" onclick="return confirmSubmit()">
                                    <i class="fas fa-paper-plane"></i> Завершить тест
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="questions-nav-grid" id="questions-nav">
                @foreach($test->questions as $index => $question)
                    @php
                        $questionId = $question->id;
                    @endphp
                    <button type="button"
                            class="nav-btn"
                            data-question-id="{{ $questionId }}"
                            onclick="goToQuestion({{ $questionId }})">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </form>

        <div class="test-footer">
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Внимание:</strong> Не обновляйте страницу во время теста. Все ответы сохраняются автоматически.
                </div>
            </div>
        </div>
    </div>

    <style>
        .test-taking-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .test-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .test-info {
            flex: 1;
        }

        .test-info h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .test-subtitle {
            color: var(--ultimate-gray);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .test-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--grenadine);
        }

        .test-timer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .timer-box {
            text-align: center;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
            border-radius: 15px;
            color: white;
            min-width: 150px;
        }

        .timer-label {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .timer-display {
            font-size: 2rem;
            font-weight: 700;
            font-family: monospace;
        }

        .questions-counter {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--black-beauty);
        }

        .test-progress {
            margin-bottom: 2rem;
        }

        .progress-bar {
            height: 8px;
            background: var(--nimbus-cloud);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: var(--grenadine);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-label {
            text-align: right;
            font-size: 0.9rem;
            color: var(--ultimate-gray);
        }

        .questions-container {
            margin-bottom: 2rem;
        }

        .question-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--nimbus-cloud);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--nimbus-cloud);
        }

        .question-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--black-beauty);
        }

        .question-points {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--grenadine);
            font-weight: 600;
        }

        .question-content {
            margin-bottom: 2rem;
        }

        .question-text {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: var(--black-beauty);
        }

        .options-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .option-label {
            display: block;
            cursor: pointer;
        }

        .option-input {
            display: none;
        }

        .option-input:checked + .option-content {
            background: rgba(223, 63, 50, 0.1);
            border-color: var(--grenadine);
        }

        .option-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .option-content:hover {
            border-color: var(--grenadine);
            background: rgba(223, 63, 50, 0.05);
        }

        .option-letter {
            width: 40px;
            height: 40px;
            background: var(--nimbus-cloud);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--black-beauty);
            flex-shrink: 0;
        }

        .option-text {
            flex: 1;
            font-size: 1rem;
            line-height: 1.5;
        }

        .short-answer-container {
            margin-top: 1rem;
        }

        .short-answer-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .short-answer-input:focus {
            border-color: var(--grenadine);
            outline: none;
            box-shadow: 0 0 0 3px rgba(223, 63, 50, 0.1);
        }

        .question-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--nimbus-cloud);
        }

        .nav-status {
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .questions-nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .nav-btn {
            width: 50px;
            height: 50px;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            background: white;
            font-weight: 600;
            color: var(--black-beauty);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-btn:hover {
            border-color: var(--grenadine);
            background: rgba(223, 63, 50, 0.05);
        }

        .nav-btn.active {
            background: var(--grenadine);
            color: white;
            border-color: var(--grenadine);
        }

        .nav-btn.answered {
            background: rgba(223, 63, 50, 0.1);
            border-color: rgba(223, 63, 50, 0.3);
        }

        .test-footer {
            margin-top: 2rem;
        }

        .warning-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            color: #856404;
        }

        .warning-box i {
            font-size: 1.5rem;
        }

        @media (max-width: 768px) {
            .test-header {
                flex-direction: column;
                align-items: stretch;
            }

            .test-timer {
                align-items: stretch;
            }

            .timer-box {
                width: 100%;
            }

            .question-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .questions-nav-grid {
                grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            }

            .nav-btn {
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        let currentQuestionId = null;
        let totalQuestions = {{ $test->questions->count() }};
        let timeLeft = {{ $test->time_limit ? $test->time_limit * 60 : 0 }};
        let timerInterval = null;
        let answers = {};

        document.addEventListener('DOMContentLoaded', function() {
            initializeTest();
            loadSavedAnswers();
            updateProgress();
            startTimer();
        });

        function initializeTest() {
            const firstQuestion = document.querySelector('.question-card');
            if (firstQuestion) {
                currentQuestionId = firstQuestion.getAttribute('id').replace('question-', '');
                updateNavigationButtons();
            }

            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const questionId = this.getAttribute('data-question-id');
                    goToQuestion(questionId);
                });
            });
        }

        function loadSavedAnswers() {
            const savedAnswers = sessionStorage.getItem(`test_{{ $testResult->id }}_answers`);
            if (savedAnswers) {
                answers = JSON.parse(savedAnswers);

                Object.keys(answers).forEach(questionId => {
                    const answer = answers[questionId];
                    const questionElement = document.getElementById(`question-${questionId}`);

                    if (questionElement) {
                        if (typeof answer === 'string') {
                            const input = questionElement.querySelector(`input[name="answers[${questionId}]"]`);
                            if (input && input.type === 'radio') {
                                const radio = questionElement.querySelector(`input[name="answers[${questionId}]"][value="${answer}"]`);
                                if (radio) radio.checked = true;
                            } else if (input && input.type === 'text') {
                                input.value = answer;
                            }
                        } else if (typeof answer === 'number') {
                            const radio = questionElement.querySelector(`input[name="answers[${questionId}]"][value="${answer}"]`);
                            if (radio) radio.checked = true;
                        }

                        updateQuestionStatus(questionId);
                    }
                });
            }
        }

        function saveAnswer(questionId, answer) {
            answers[questionId] = answer;
            sessionStorage.setItem(`test_{{ $testResult->id }}_answers`, JSON.stringify(answers));
            updateQuestionStatus(questionId);
            updateProgress();
        }

        function updateQuestionStatus(questionId) {
            const navBtn = document.querySelector(`.nav-btn[data-question-id="${questionId}"]`);
            const questionElement = document.getElementById(`question-${questionId}`);
            const answerCountElement = document.getElementById(`answered-count-${questionId}`);

            if (navBtn) {
                if (answers[questionId] !== undefined) {
                    navBtn.classList.add('answered');
                } else {
                    navBtn.classList.remove('answered');
                }
            }

            if (answerCountElement) {
                answerCountElement.textContent = answers[questionId] !== undefined ? 1 : 0;
            }
        }

        function updateProgress() {
            const answeredCount = Object.keys(answers).length;
            const progress = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;

            document.getElementById('progress-fill').style.width = `${progress}%`;
            document.getElementById('progress-text').textContent = Math.round(progress);

            document.getElementById('current-question').textContent = getCurrentQuestionIndex() + 1;
        }

        function getCurrentQuestionIndex() {
            if (!currentQuestionId) return 0;
            const questionElement = document.getElementById(`question-${currentQuestionId}`);
            if (questionElement) {
                return parseInt(questionElement.getAttribute('data-question-index'));
            }
            return 0;
        }

        function goToQuestion(questionId) {
            if (currentQuestionId) {
                document.getElementById(`question-${currentQuestionId}`).style.display = 'none';
                document.querySelector(`.nav-btn[data-question-id="${currentQuestionId}"]`).classList.remove('active');
            }

            currentQuestionId = questionId;
            document.getElementById(`question-${questionId}`).style.display = 'block';
            document.querySelector(`.nav-btn[data-question-id="${questionId}"]`).classList.add('active');

            updateProgress();
        }

        function nextQuestion() {
            const currentIndex = getCurrentQuestionIndex();
            if (currentIndex < totalQuestions - 1) {
                const nextQuestion = document.querySelector(`.question-card[data-question-index="${currentIndex + 1}"]`);
                if (nextQuestion) {
                    const nextQuestionId = nextQuestion.id.replace('question-', '');
                    goToQuestion(nextQuestionId);
                }
            }
        }

        function prevQuestion() {
            const currentIndex = getCurrentQuestionIndex();
            if (currentIndex > 0) {
                const prevQuestion = document.querySelector(`.question-card[data-question-index="${currentIndex - 1}"]`);
                if (prevQuestion) {
                    const prevQuestionId = prevQuestion.id.replace('question-', '');
                    goToQuestion(prevQuestionId);
                }
            }
        }

        function startTimer() {
            if (timeLeft <= 0) return;

            const timerElement = document.getElementById('timer');

            timerInterval = setInterval(() => {
                timeLeft--;

                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 300) {
                    timerElement.style.color = '#dc3545';
                }

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert('Время вышло! Тест будет автоматически отправлен.');
                    document.getElementById('test-form').submit();
                }
            }, 1000);
        }

        function confirmSubmit() {
            const answeredCount = Object.keys(answers).length;
            const unansweredCount = totalQuestions - answeredCount;

            if (unansweredCount > 0) {
                return confirm(`У вас есть ${unansweredCount} неотвеченных вопросов. Вы уверены, что хотите завершить тест?`);
            }

            return true;
        }

        window.addEventListener('beforeunload', function(e) {
            if (Object.keys(answers).length > 0) {
                e.preventDefault();
                e.returnValue = 'Вы покидаете страницу теста. Ваши ответы сохранены, но тест не будет завершен.';
            }
        });
    </script>
@endsection
