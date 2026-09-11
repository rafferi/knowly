<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestResult;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $user = Auth::user();

        $tests = Test::where('course_id', $course->id)
            ->where('is_active', true)
            ->orderBy('week_number')
            ->get()
            ->groupBy('type');

        // Получаем результаты тестов пользователя
        $userResults = TestResult::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->get()
            ->keyBy('test_id');

        return view('tests.index', compact('course', 'tests', 'userResults'));
    }

    public function show(Test $test)
    {
        $user = Auth::user();

        // Проверяем доступ
        if (!$this->canAccessTest($user, $test)) {
            return redirect()->route('tests.index', $test->course->slug)
                ->with('error', 'У вас нет доступа к этому тесту');
        }

        // Получаем предыдущие попытки
        $attempts = TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->orderBy('attempt_number', 'desc')
            ->get();

        $canTakeTest = $attempts->count() < $test->max_attempts;
        $nextAttempt = $attempts->count() + 1;

        return view('tests.show', compact('test', 'attempts', 'canTakeTest', 'nextAttempt'));
    }

    public function start(Test $test)
    {
        $user = Auth::user();

        // Проверяем доступ
        if (!$this->canAccessTest($user, $test)) {
            return redirect()->route('tests.index', $test->course->slug)
                ->with('error', 'У вас нет доступа к этому тесту');
        }

        // Проверяем количество попыток
        $attemptsCount = TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->count();

        if ($attemptsCount >= $test->max_attempts) {
            return redirect()->route('tests.show', $test)
                ->with('error', 'Вы исчерпали все попытки для этого тесту');
        }

        // Создаем запись о начале теста
        $testResult = TestResult::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'course_id' => $test->course_id,
            'attempt_number' => $attemptsCount + 1,
            'started_at' => now(),
            'answers' => [],
            'question_details' => $test->questions
        ]);

        return view('tests.take', compact('test', 'testResult'));
    }

    public function submit(Request $request, Test $test)
    {
        $user = Auth::user();

        // Находим активную попытку
        $testResult = TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->latest()
            ->firstOrFail();

        // Проверяем время
        $timeSpent = now()->diffInSeconds($testResult->started_at);

        // Проверяем ответы
        $answers = $request->input('answers', []);
        $score = 0;
        $correctAnswers = 0;
        $totalQuestions = count($test->questions);
        $correctAnswersList = [];

        foreach ($test->questions as $index => $question) {
            if (!isset($question['id'])) {
                $question['id'] = $index;
            }

            $userAnswer = $answers[$question['id']] ?? null;
            $isCorrect = $userAnswer == $question['correct_answer'];

            if ($isCorrect) {
                $score += $question['points'] ?? 1;
                $correctAnswers++;
                $correctAnswersList[$question['id']] = true;
            } else {
                $correctAnswersList[$question['id']] = false;
            }
        }

        // Рассчитываем процент
        $percentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $percentage >= $test->passing_score;

        // Обновляем результат
        $testResult->update([
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'passed' => $passed,
            'completed_at' => now(),
            'time_spent' => $timeSpent,
            'answers' => $answers,
            'correct_answers_list' => $correctAnswersList,
            'feedback' => $passed ? 'Тест пройден успешно!' : 'Тест не пройден. Попробуйте еще раз.'
        ]);

        // Обновляем прогресс пользователя
        $this->updateUserProgress($user, $test, $passed);

        return redirect()->route('tests.results', $testResult)
            ->with('success', $passed ? 'Поздравляем! Тест пройден.' : 'Тест не пройден.');
    }

    public function results(TestResult $testResult)
    {
        // Проверяем права доступа
        if ($testResult->user_id !== Auth::id() && !Auth::user()->isTeacher()) {
            abort(403);
        }

        $testResult->load(['test', 'user', 'course']);

        // Получаем детали вопросов
        $questionsWithAnswers = [];
        foreach ($testResult->test->questions as $index => $question) {
            if (!isset($question['id'])) {
                $question['id'] = $index;
            }

            $userAnswer = $testResult->answers[$question['id']] ?? null;
            $isCorrect = $testResult->correct_answers_list[$question['id']] ?? false;

            $questionsWithAnswers[] = [
                'question' => $question['question'],
                'options' => $question['options'],
                'user_answer' => $userAnswer,
                'correct_answer' => $question['correct_answer'],
                'is_correct' => $isCorrect,
                'explanation' => $question['explanation'] ?? null
            ];
        }

        return view('tests.results', compact('testResult', 'questionsWithAnswers'));
    }

    private function canAccessTest($user, $test)
    {
        // Проверяем, активен ли тест
        if (!$test->is_active) {
            return false;
        }

        // Проверяем доступ по неделям (если тест привязан к неделе)
//        if ($test->week_number) {
//            $userProgress = $user->userLearningProgress()
//                ->where('course_id', $test->course_id)
//                ->first();
//
//            if ($userProgress && $test->week_number > $userProgress->current_week) {
//                return false;
//            }
//        }

        return true;
    }

    private function updateUserProgress($user, $test, $passed)
    {
        if ($passed) {
            // Обновляем прогресс обучения
            $progress = $user->userLearningProgress()
                ->where('course_id', $test->course_id)
                ->first();

            if ($progress) {
                // Добавляем XP за успешный тест
                $user->increment('xp', 50);

                // Обновляем статистику
                $progress->increment('tests_passed');
                $progress->save();
            }
        }
    }
}
