<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\TestResult;
use App\Models\UserLearningProgress;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniversalTestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Получаем все активные курсы пользователя
        $activeCourses = UserCourse::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($activeCourses->isEmpty()) {
            $placementTest = Test::where('is_active', true)
                ->where('type', 'placement')
                ->with(['course', 'module'])
                ->first();

            $tests = collect();
            if ($placementTest) {
                $tests['placement'] = collect([$placementTest]);
            }

            $userResults = collect();
            return view('tests.universal-index', compact('tests', 'userResults', 'activeCourses'))
                ->with('message', 'Вы не записаны ни на один курс. Доступен только placement test.');
        }

        $courseIds = $activeCourses->pluck('course_id')->toArray();
        $tests = Test::where('is_active', true)
            ->where(function($query) use ($courseIds) {
                $query->where('type', 'placement')
                    ->orWhereIn('course_id', $courseIds);
            })
            ->with(['course', 'module'])
            ->orderBy('type')
            ->orderBy('order_index')
            ->get()
            ->groupBy('type');

        if ($tests->has('placement')) {
            $placementTests = $tests['placement'];
            if ($placementTests->isNotEmpty()) {
                $tests['placement'] = collect([$placementTests->first()]);
            }
        }

        $userResults = TestResult::where('user_id', $user->id)
            ->whereIn('test_id', $tests->flatten()->pluck('id'))
            ->get()
            ->keyBy('test_id');

        $allUserCourses = $activeCourses;

        return view('tests.universal-index', compact('tests', 'userResults', 'allUserCourses', 'activeCourses'));
    }

    public function show($testId)
    {
        $user = Auth::user();
        $test = Test::with(['course', 'module', 'questions'])->findOrFail($testId);

        if (!$this->checkTestAccess($user, $test)) {
            return redirect()->route('universal-tests.index')
                ->with('error', 'У вас нет доступа к этому тесту');
        }

        $attempts = TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->orderBy('attempt_number', 'desc')
            ->get();

        $canTakeTest = $attempts->count() < $test->max_attempts;
        $nextAttempt = $attempts->count() + 1;
        $bestResult = $attempts->sortByDesc('score')->first();

        return view('tests.universal-show', compact('test', 'attempts', 'canTakeTest', 'nextAttempt', 'bestResult'));
    }

    public function start($testId)
    {
        $user = Auth::user();
        $test = Test::with(['questions'])->findOrFail($testId);

        if (!$this->checkTestAccess($user, $test)) {
            return redirect()->route('universal-tests.index')
                ->with('error', 'У вас нет доступа к этому тесту');
        }

        $attemptsCount = TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->count();

        if ($attemptsCount >= $test->max_attempts) {
            return redirect()->route('universal-tests.show', $test->id)
                ->with('error', 'Вы исчерпали все попытки для этого теста');
        }

        $testResult = TestResult::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'course_id' => $test->course_id,
            'attempt_number' => $attemptsCount + 1,
            'started_at' => now(),
            'answers' => [],
            'question_details' => $test->questions->map(function($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'options' => $question->options,
                    'correct_answer' => $question->correct_answer,
                    'points' => $question->points,
                    'explanation' => $question->explanation
                ];
            })->toArray()
        ]);

        return view('tests.take', compact('test', 'testResult'));
    }

    public function submit(Request $request, $testId)
    {
        $user = Auth::user();
        $test = Test::with(['questions'])->findOrFail($testId);

        if (!$this->checkTestAccess($user, $test)) {
            return redirect()->route('universal-tests.index')
                ->with('error', 'У вас нет доступа к этому тесту');
        }

        $testResult = TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->latest()
            ->first();

        if (!$testResult) {
            return redirect()->route('universal-tests.show', $test->id)
                ->with('error', 'Не найдено активной попытки теста. Начните тест заново.');
        }

        $timeSpent = now()->diffInSeconds($testResult->started_at);
        if ($test->time_limit && $timeSpent > ($test->time_limit * 60)) {
            $timeSpent = $test->time_limit * 60;
        }

        $answers = $request->input('answers', []);
        $score = 0;
        $correctAnswers = 0;
        $totalQuestions = $test->questions->count();
        $correctAnswersList = [];

        foreach ($test->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = false;

            if ($question->type === 'multiple_choice') {
                $isCorrect = (int)$userAnswer === (int)$question->correct_answer;
            } elseif ($question->type === 'true_false') {
                $isCorrect = $userAnswer === $question->correct_answer;
            } elseif ($question->type === 'short_answer') {
                $correctAnswer = strtolower(trim($question->correct_answer));
                $userAnswerClean = strtolower(trim($userAnswer));
                $isCorrect = $userAnswerClean === $correctAnswer;
            }

            if ($isCorrect) {
                $score += $question->points;
                $correctAnswers++;
                $correctAnswersList[$question->id] = true;
            } else {
                $correctAnswersList[$question->id] = false;
            }
        }

        $percentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $percentage >= $test->passing_score;

        $testResult->update([
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'passed' => $passed,
            'completed_at' => now(),
            'time_spent' => $timeSpent,
            'answers' => $answers,
            'correct_answers_list' => $correctAnswersList
        ]);

        $this->updateUserProgress($user, $test, $passed, $score, $correctAnswers);

        return redirect()->route('universal-tests.results', $testResult->id)
            ->with('success', $passed ? 'Поздравляем! Тест пройден.' : 'Тест не пройден.');
    }

    public function results($resultId)
    {
        $testResult = TestResult::with(['test', 'test.questions', 'test.course', 'user'])
            ->findOrFail($resultId);

        if ($testResult->user_id !== Auth::id() && !Auth::user()->isTeacher()) {
            abort(403);
        }

        $questionsWithAnswers = [];
        foreach ($testResult->test->questions as $question) {
            $userAnswer = $testResult->answers[$question->id] ?? null;
            $isCorrect = $testResult->correct_answers_list[$question->id] ?? false;

            $questionsWithAnswers[] = [
                'id' => $question->id,
                'question' => $question->question,
                'type' => $question->type,
                'options' => $question->options ?? [],
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'points' => $question->points,
                'explanation' => $question->explanation
            ];
        }

        return view('tests.universal-results', compact('testResult', 'questionsWithAnswers'));
    }

    public function placement()
    {
        $user = Auth::user();

        $test = Test::where('type', 'placement')
            ->where('is_active', true)
            ->with(['course', 'module', 'questions'])
            ->first();

        if (!$test) {
            abort(404, 'Placement test not found');
        }

        return redirect()->route('universal-tests.show', $test->id);
    }

    private function checkTestAccess($user, $test)
    {
        if ($test->type === 'placement') {
            $mainPlacementTest = Test::where('is_active', true)
                ->where('type', 'placement')
                ->first();

            return $mainPlacementTest && $test->id === $mainPlacementTest->id;
        }

        // Проверяем, записан ли пользователь на курс, к которому относится тест
        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $test->course_id)
            ->whereNull('completed_at')
            ->first();

        if (!$userCourse) {
            return false;
        }

        return $test->isAccessibleForUser($user);
    }

    private function updateUserProgress($user, $test, $passed, $score, $correctAnswers)
    {
        if ($passed) {
            $xpAmount = $correctAnswers * 10;
            $user->addXp($xpAmount, 'test', $test->id);

            if ($test->type !== 'placement' && $test->type !== 'practice') {
                $courseProgress = DB::table('user_course_progress')
                    ->where('user_id', $user->id)
                    ->where('course_id', $test->course_id)
                    ->first();

                if ($courseProgress) {
                    DB::table('user_course_progress')
                        ->where('user_id', $user->id)
                        ->where('course_id', $test->course_id)
                        ->update([
                            'completed_tests' => $courseProgress->completed_tests + 1,
                            'last_activity_at' => now(),
                            'updated_at' => now()
                        ]);
                }

                $progress = UserLearningProgress::where('user_id', $user->id)
                    ->where('course_id', $test->course_id)
                    ->first();

                if ($progress) {
                    $progress->last_study_date = now();
                    $progress->save();
                }
            }
        }
    }
}
