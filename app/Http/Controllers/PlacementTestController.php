<?php
// [file name]: PlacementTestController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlacementTest;
use App\Models\PlacementQuestion;
use Illuminate\Support\Facades\Auth;

class PlacementTestController extends Controller
{
    public function show()
    {
        // Получаем вопросы и группируем по уровню как коллекцию
        $questions = PlacementQuestion::orderBy('level')
            ->orderBy('order')
            ->get()
            ->groupBy('level');

        return view('placement-test', [
            'questions' => $questions
        ]);
    }

    // Остальные методы оставляем без изменений...
    public function store(Request $request)
    {
        $user = Auth::user();
        $answers = $request->input('answers', []);
        $score = 0;
        $totalQuestions = 0;
        $correctAnswers = 0;

        // Получаем все вопросы из базы
        $questions = PlacementQuestion::all();

        // Проверяем ответы для каждого вопроса
        foreach ($questions as $question) {
            $questionKey = $question->level . '_' . ($question->order - 1);
            $totalQuestions++;

            if (isset($answers[$questionKey]) && $answers[$questionKey] == $question->correct_index) {
                $score += 1;
                $correctAnswers++;
            }
        }

        // Определяем уровень на основе баллов
        $level = $this->calculateLevel($score, $totalQuestions);

        // Сохраняем результаты
        $test = PlacementTest::create([
            'user_id' => $user->id,
            'score' => $score,
            'level' => $level,
            'answers' => $answers,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'completed_at' => now(),
        ]);

        // Обновляем уровень пользователя
        $user->update([
            'level' => $level,
            'xp' => $user->xp + $score * 10,
        ]);

        return redirect()->route('placement-test.results', $test);
    }

    public function results(PlacementTest $test)
    {
        $levelTitles = [
            'beginner' => 'Начинающий (A1)',
            'elementary' => 'Элементарный (A2)',
            'intermediate' => 'Средний (B1)',
            'upper_intermediate' => 'Выше среднего (B2)',
            'advanced' => 'Продвинутый (C1)',
            'master' => 'Мастер (C2)'
        ];

        // Рекомендованные курсы в зависимости от уровня
        $recommendedCourses = $this->getRecommendedCourses($test->level);

        return view('placement-test-results', compact('test', 'levelTitles', 'recommendedCourses'));
    }

    private function calculateLevel($score, $totalQuestions)
    {
        $percentage = ($score / $totalQuestions) * 100;

        if ($percentage < 20) return 'beginner';
        if ($percentage < 40) return 'elementary';
        if ($percentage < 60) return 'intermediate';
        if ($percentage < 80) return 'upper_intermediate';
        return 'advanced';
    }

    private function getRecommendedCourses($level)
    {
        $courses = [
            'beginner' => [
                [
                    'title' => 'English Starter',
                    'description' => 'Основы английского для начинающих',
                    'level' => 'A1-A2',
                    'link' => '/courses?level=beginner'
                ],
                [
                    'title' => 'Everyday English',
                    'description' => 'Повседневные фразы и выражения',
                    'level' => 'A1-A2',
                    'link' => '/courses?level=beginner'
                ]
            ],
            'elementary' => [
                [
                    'title' => 'Elementary English',
                    'description' => 'Улучшите базовые навыки общения',
                    'level' => 'A2',
                    'link' => '/courses?level=elementary'
                ],
                [
                    'title' => 'Travel English',
                    'description' => 'Английский для путешествий',
                    'level' => 'A2-B1',
                    'link' => '/courses?level=elementary'
                ]
            ],
            'intermediate' => [
                [
                    'title' => 'Conversational Pro',
                    'description' => 'Свободное общение на английском',
                    'level' => 'B1',
                    'link' => '/courses?level=intermediate'
                ],
                [
                    'title' => 'Business English Basics',
                    'description' => 'Деловой английский для работы',
                    'level' => 'B1-B2',
                    'link' => '/courses?level=intermediate'
                ]
            ],
            'upper_intermediate' => [
                [
                    'title' => 'Business English Pro',
                    'description' => 'Продвинутый деловой английский',
                    'level' => 'B2',
                    'link' => '/courses?level=upper_intermediate'
                ],
                [
                    'title' => 'Academic Writing',
                    'description' => 'Академическое письмо и эссе',
                    'level' => 'B2-C1',
                    'link' => '/courses?level=upper_intermediate'
                ]
            ],
            'advanced' => [
                [
                    'title' => 'IELTS Preparation',
                    'description' => 'Подготовка к международному экзамену',
                    'level' => 'C1',
                    'link' => '/courses?level=advanced'
                ],
                [
                    'title' => 'Advanced Communication',
                    'description' => 'Продвинутое общение и дебаты',
                    'level' => 'C1-C2',
                    'link' => '/courses?level=advanced'
                ]
            ]
        ];

        return $courses[$level] ?? $courses['beginner'];
    }
}
