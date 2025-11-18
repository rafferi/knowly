@extends('app')

@section('title', 'Тест на определение уровня - Knowly')

@section('content')
    <div class="placement-test-container">
        <div class="profile-card placement-test-card">
            <div class="placement-test-header">
                <div class="test-title">Тест на определение уровня английского</div>
                @php
                    $totalQuestions = $questions->flatten()->count();
                @endphp
                <p class="test-subtitle">Пройдите тест из {{ $totalQuestions }} вопросов, чтобы мы могли определить ваш текущий уровень и подобрать подходящие курсы</p>

                <div class="test-progress">
                    <div class="progress-info">
                        <span>Прогресс</span>
                        <span><span class="current-question">1</span>/{{ $totalQuestions }}</span>
                    </div>
                    <div class="progress-bar-test">
                        <div class="progress-fill-test" style="width: 4%"></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('placement-test.store') }}" method="POST" id="placement-test-form">
                @csrf

                <div class="test-questions">
                    @php
                        $questionIndex = 1;
                    @endphp

                    @if($totalQuestions > 0)
                        @foreach($questions as $level => $levelQuestions)
                            @foreach($levelQuestions as $question)
                                <div class="question-card" data-question="{{ $questionIndex }}">
                                    <div class="question-header">
                                        <div class="question-number">Вопрос {{ $questionIndex }}</div>
                                        <div class="question-level">{{ ucfirst(str_replace('_', ' ', $level)) }}</div>
                                    </div>

                                    <div class="question-text">{{ $question->question }}</div>

                                    <div class="options-grid">
                                        @php
                                            // Гарантируем, что options - это массив
                                            $options = $question->options;
                                            if (is_string($options)) {
                                                $options = json_decode($options, true) ?? [];
                                            }
                                            if (!is_array($options)) {
                                                $options = [];
                                            }
                                        @endphp

                                        @if(count($options) > 0)
                                            @foreach($options as $optionIndex => $option)
                                                <div class="option-card" data-option="{{ $optionIndex }}">
                                                    <div class="option-content">
                                                        <div class="option-marker"></div>
                                                        <div class="option-text">{{ $option }}</div>
                                                    </div>
                                                    <input type="radio"
                                                           name="answers[{{ $level }}_{{ $question->order - 1 }}]"
                                                           value="{{ $optionIndex }}"
                                                           class="placement-test-option-input"
                                                           style="display: none;">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="no-options">
                                                <p>Варианты ответа временно недоступны.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @php $questionIndex++; @endphp
                            @endforeach
                        @endforeach
                    @else
                        <div class="no-questions">
                            <p>Вопросы для тестирования временно недоступны.</p>
                        </div>
                    @endif
                </div>

                @if($totalQuestions > 0)
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
                            Завершить тест и узнать результат
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @if($totalQuestions > 0)
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
                const form = document.getElementById('placement-test-form');
                form.addEventListener('submit', function(e) {
                    const answeredQuestions = document.querySelectorAll('.placement-test-option-input:checked').length;

                    if (answeredQuestions < totalQuestions) {
                        e.preventDefault();
                        if (confirm(`Вы ответили только на ${answeredQuestions} из ${totalQuestions} вопросов. Хотите продолжить?`)) {
                            form.submit();
                        }
                    }
                });

                // Инициализация
                showQuestion(1);
            });
        </script>
    @endif
@endsection
