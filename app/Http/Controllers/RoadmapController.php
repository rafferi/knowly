<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\UserLesson;
use App\Models\TestResult;
use App\Models\UserCourse;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class RoadmapController extends Controller
{
    public function show($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $user = Auth::user();

        // Получаем все уроки курса
        $lessons = Lesson::where('course_id', $course->id)
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->get();

        // Получаем все тесты курса (кроме placement)
        $tests = Test::where('course_id', $course->id)
            ->where('is_active', true)
            ->where('type', '!=', 'placement')
            ->orderBy('order_index', 'asc')
            ->get();

        // Получаем прогресс пользователя
        $userLessons = [];
        $userTests = [];

        if ($user) {
            $userLessons = UserLesson::where('user_id', $user->id)
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->get()
                ->keyBy('lesson_id');

            $userTests = TestResult::where('user_id', $user->id)
                ->whereIn('test_id', $tests->pluck('id'))
                ->whereNotNull('completed_at')
                ->get()
                ->groupBy('test_id')
                ->map(function ($results) {
                    return $results->sortByDesc('score')->first();
                });
        }

        // Создаем чередующуюся последовательность: урок → тест → урок → тест
        $roadmapItems = collect();

        $lessonCount = $lessons->count();
        $testCount = $tests->count();
        $maxCount = max($lessonCount, $testCount);

        // Группируем тесты по привязке к урокам
        $testsByLesson = $tests->groupBy('lesson_id');

        for ($i = 0; $i < $maxCount; $i++) {
            // Добавляем урок, если есть
            if (isset($lessons[$i])) {
                $lesson = $lessons[$i];
                $userLesson = $userLessons[$lesson->id] ?? null;
                $lessonCompleted = $userLesson && $userLesson->completed;

                $roadmapItems->push([
                    'type' => 'lesson',
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'description' => $lesson->description,
                    'item' => $lesson,
                    'user_progress' => $userLesson,
                    'is_completed' => $lessonCompleted,
                    'is_available' => true,
                    'order' => $i * 2, // Четные позиции для уроков
                ]);

                // Добавляем тест, связанный с этим уроком
                $lessonTests = $testsByLesson[$lesson->id] ?? collect();
                if ($lessonTests->isNotEmpty()) {
                    $test = $lessonTests->first();
                    $bestResult = $userTests[$test->id] ?? null;
                    $testCompleted = $bestResult && $bestResult->passed;

                    $roadmapItems->push([
                        'type' => 'test',
                        'id' => $test->id,
                        'title' => $test->title,
                        'description' => $test->description,
                        'item' => $test,
                        'user_progress' => $bestResult,
                        'is_completed' => $testCompleted,
                        'is_available' => $lessonCompleted, // Тест доступен только после завершения урока
                        'order' => $i * 2 + 1, // Нечетные позиции для тестов
                        'linked_lesson_id' => $lesson->id,
                    ]);
                }
            }

            // Добавляем тесты без привязки к урокам (если уроков меньше, чем тестов)
            if (!isset($lessons[$i]) && isset($tests[$i])) {
                $test = $tests[$i];
                // Пропускаем тесты, которые уже были добавлены через уроки
                if (!$roadmapItems->contains('id', $test->id) && $test->type !== 'placement') {
                    $bestResult = $userTests[$test->id] ?? null;
                    $testCompleted = $bestResult && $bestResult->passed;

                    $roadmapItems->push([
                        'type' => 'test',
                        'id' => $test->id,
                        'title' => $test->title,
                        'description' => $test->description,
                        'item' => $test,
                        'user_progress' => $bestResult,
                        'is_completed' => $testCompleted,
                        'is_available' => true,
                        'order' => $i * 2 + 1,
                        'linked_lesson_id' => null,
                    ]);
                }
            }
        }

        // Сортируем по order
        $roadmapItems = $roadmapItems->sortBy('order')->values();

        // Определяем доступность элементов
        $previousCompleted = true;

        foreach ($roadmapItems as $key => &$item) {
            // Первый элемент всегда доступен
            if ($key === 0) {
                $item['is_available'] = true;
            } else {
                // Элемент доступен, если предыдущий завершен
                $previousItem = $roadmapItems[$key - 1];
                $item['is_available'] = $previousItem['is_completed'] ?? false;
            }

            // Если это тест, привязанный к уроку, дополнительная проверка
            if ($item['type'] === 'test' && isset($item['linked_lesson_id']) && $item['linked_lesson_id']) {
                $lessonId = $item['linked_lesson_id'];
                $lessonCompleted = isset($userLessons[$lessonId]) && $userLessons[$lessonId]->completed;
                $item['is_available'] = $item['is_available'] && $lessonCompleted;
            }

            // Гарантируем наличие ключей
            $item['is_available'] = $item['is_available'] ?? false;
            $item['is_completed'] = $item['is_completed'] ?? false;
        }

        // Проверяем, завершен ли курс на 100%
        $courseCompleted = false;
        $userCourse = null;
        $certificate = null;

        if ($user) {
            $userCourse = UserCourse::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($userCourse && $userCourse->progress >= 100) {
                $courseCompleted = true;

                // Проверяем, есть ли сертификат
                $certificate = Certificate::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();
            }
        }

        return view('roadmap.show', [
            'course' => $course,
            'roadmapItems' => $roadmapItems,
            'user' => $user,
            'courseCompleted' => $courseCompleted,
            'userCourse' => $userCourse,
            'certificate' => $certificate
        ]);
    }

    public function takeLesson($courseSlug, $lessonId)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $lesson = Lesson::where('course_id', $course->id)
            ->where('id', $lessonId)
            ->firstOrFail();

        $user = Auth::user();

        // Создаем запись о начале урока
        $userLesson = UserLesson::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        return redirect()->route('lectures.show', [
            'courseSlug' => $course->slug,
            'lessonId' => $lessonId
        ]);
    }

    public function takeTest($courseSlug, $testId)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $test = Test::where('course_id', $course->id)
            ->where('id', $testId)
            ->firstOrFail();

        return redirect()->route('universal-tests.start', $testId);
    }

    public function getNextAvailableItem($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $user = Auth::user();

        // Получаем все уроки
        $lessons = Lesson::where('course_id', $course->id)
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->get();

        // Получаем все тесты
        $tests = Test::where('course_id', $course->id)
            ->where('is_active', true)
            ->where('type', '!=', 'placement')
            ->orderBy('order_index', 'asc')
            ->get();

        $userLessons = UserLesson::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        $userTests = TestResult::where('user_id', $user->id)
            ->whereIn('test_id', $tests->pluck('id'))
            ->whereNotNull('completed_at')
            ->get()
            ->groupBy('test_id')
            ->map(function ($results) {
                return $results->sortByDesc('score')->first();
            });

        // Создаем последовательность для поиска
        $sequence = collect();
        $testsByLesson = $tests->groupBy('lesson_id');

        $lessonCount = $lessons->count();
        $testCount = $tests->count();
        $maxCount = max($lessonCount, $testCount);

        for ($i = 0; $i < $maxCount; $i++) {
            // Добавляем урок
            if (isset($lessons[$i])) {
                $lesson = $lessons[$i];
                $userLesson = $userLessons[$lesson->id] ?? null;
                $lessonCompleted = $userLesson && $userLesson->completed;

                $sequence->push([
                    'type' => 'lesson',
                    'id' => $lesson->id,
                    'is_completed' => $lessonCompleted,
                    'order' => $i * 2,
                ]);

                // Добавляем тест для этого урока
                $lessonTests = $testsByLesson[$lesson->id] ?? collect();
                if ($lessonTests->isNotEmpty()) {
                    $test = $lessonTests->first();
                    $bestResult = $userTests[$test->id] ?? null;
                    $testCompleted = $bestResult && $bestResult->passed;

                    $sequence->push([
                        'type' => 'test',
                        'id' => $test->id,
                        'is_completed' => $testCompleted && $lessonCompleted, // Тест считается завершенным только если и тест, и урок завершены
                        'order' => $i * 2 + 1,
                    ]);
                }
            }

            // Добавляем тесты без уроков
            if (!isset($lessons[$i]) && isset($tests[$i])) {
                $test = $tests[$i];
                if (!$sequence->contains('id', $test->id)) {
                    $bestResult = $userTests[$test->id] ?? null;
                    $testCompleted = $bestResult && $bestResult->passed;

                    $sequence->push([
                        'type' => 'test',
                        'id' => $test->id,
                        'is_completed' => $testCompleted,
                        'order' => $i * 2 + 1,
                    ]);
                }
            }
        }

        // Сортируем по порядку
        $sequence = $sequence->sortBy('order')->values();

        // Ищем первый незавершенный элемент
        foreach ($sequence as $item) {
            if (!$item['is_completed']) {
                if ($item['type'] === 'lesson') {
                    return redirect()->route('roadmap.take-lesson', [
                        'courseSlug' => $courseSlug,
                        'lessonId' => $item['id']
                    ]);
                } else {
                    return redirect()->route('roadmap.take-test', [
                        'courseSlug' => $courseSlug,
                        'testId' => $item['id']
                    ]);
                }
            }
        }

        // Если все пройдено
        return redirect()->route('certificate.show', $courseSlug)
            ->with('success', 'Вы завершили весь курс! Получите ваш сертификат.');
    }
}
