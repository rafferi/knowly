<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\UserLesson;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{
    public function index($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$userCourse) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'Вы не записаны на этот курс');
        }

        // Получаем все лекции и тесты курса, упорядоченные по порядку
        $lessons = Lesson::where('course_id', $course->id)
            ->where('status', 'published')
            ->orderBy('order')
            ->get();

        $tests = Test::where('course_id', $course->id)
            ->where('is_active', true)
            ->where('type', '!=', 'placement')
            ->orderBy('order_index')
            ->get()
            ->groupBy('lesson_id');

        $userLessons = UserLesson::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        $userTests = TestResult::where('user_id', $user->id)
            ->whereIn('test_id', $tests->flatten()->pluck('id'))
            ->whereNotNull('completed_at')
            ->get()
            ->groupBy('test_id')
            ->map(fn($results) => $results->sortByDesc('score')->first());

        $roadmapItems = collect();

        foreach ($lessons as $lesson) {
            $lessonCompleted = isset($userLessons[$lesson->id]) && $userLessons[$lesson->id]->completed;
            $roadmapItems->push([
                'type' => 'lesson',
                'id' => $lesson->id,
                'title' => $lesson->title,
                'url' => route('lectures.show', ['courseSlug' => $courseSlug, 'lessonId' => $lesson->id]),
                'is_completed' => $lessonCompleted,
                'is_available' => true, // позже пересчитаем
            ]);

            // Тесты, привязанные к этой лекции
            $lessonTests = $tests->get($lesson->id) ?? collect();
            foreach ($lessonTests as $test) {
                $testCompleted = isset($userTests[$test->id]) && $userTests[$test->id]->passed;
                $roadmapItems->push([
                    'type' => 'test',
                    'id' => $test->id,
                    'title' => $test->title,
                    'url' => route('universal-tests.show', $test->id),
                    'is_completed' => $testCompleted,
                    'is_available' => false, // позже
                    'linked_lesson_id' => $lesson->id,
                ]);
            }
        }

        // Пересчитываем доступность: первый элемент доступен, затем каждый следующий доступен, если предыдущий завершён
        $previousCompleted = true;
        foreach ($roadmapItems as $key => &$item) {
            if ($key === 0) {
                $item['is_available'] = true;
            } else {
                $item['is_available'] = $previousCompleted;
            }
            // Для теста дополнительно проверяем, завершена ли связанная лекция
            if ($item['type'] === 'test' && isset($item['linked_lesson_id'])) {
                $lessonCompleted = isset($userLessons[$item['linked_lesson_id']]) && $userLessons[$item['linked_lesson_id']]->completed;
                $item['is_available'] = $item['is_available'] && $lessonCompleted;
            }
            $previousCompleted = $item['is_completed'];
        }

        return view('lectures.index', compact('course', 'roadmapItems'));
    }

    public function show($courseSlug, $lessonId)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$userCourse) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'Вы не записаны на этот курс');
        }

        $lesson = Lesson::where('id', $lessonId)
            ->where('course_id', $course->id)
            ->active()
            ->firstOrFail();

        $allLessons = Lesson::where('course_id', $course->id)
            ->active()
            ->orderBy('order')
            ->get();

        $userLessons = UserLesson::where('user_id', $user->id)
            ->whereIn('lesson_id', $allLessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        if (!$this->isLessonAvailable($lesson, $allLessons, $userLessons)) {
            return redirect()->route('lectures.index', $courseSlug)
                ->with('error', 'Эта лекция еще не доступна. Сначала завершите предыдущие.');
        }

        $userLesson = UserLesson::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['started_at' => now(), 'last_accessed_at' => now()]
        );

        $linkedTest = Test::where('lesson_id', $lesson->id)
            ->where('is_active', true)
            ->first();

        $nextLesson = $this->getNextLesson($allLessons, $lesson->id);
        $prevLesson = $this->getPrevLesson($allLessons, $lesson->id);

        $nextItem = null;
        if ($linkedTest) {
            $nextItem = [
                'type' => 'test',
                'id' => $linkedTest->id,
                'url' => route('universal-tests.show', $linkedTest->id)
            ];
        } elseif ($nextLesson) {
            $nextItem = [
                'type' => 'lesson',
                'id' => $nextLesson->id,
                'url' => route('lectures.show', ['courseSlug' => $courseSlug, 'lessonId' => $nextLesson->id])
            ];
        }

        return view('lectures.show', compact('course', 'lesson', 'userLesson', 'nextItem', 'prevLesson'));
    }

    public function complete(Request $request, $courseSlug, $lessonId)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();
        $lesson = Lesson::findOrFail($lessonId);

        // Отмечаем урок завершённым
        UserLesson::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lessonId],
            [
                'completed' => true,
                'completed_at' => now(),
                'time_spent' => $request->input('time_spent', 300),
                'score' => $request->input('score', 100),
                'module_id' => $lesson->module_id
            ]
        );


        // Обновляем прогресс курса
        $this->updateCourseProgress($user, $course);

        // Если запрос пришёл через AJAX (например, нажата зелёная кнопка)
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Урок завершён!']);
        }

        // Если передан redirect_url, перенаправляем туда
        if ($request->filled('redirect_url')) {
            return redirect($request->input('redirect_url'));
        }

        // Иначе ищем следующий урок или тест
        $linkedTest = Test::where('lesson_id', $lesson->id)->where('is_active', true)->first();
        if ($linkedTest) {
            return redirect()->route('universal-tests.show', $linkedTest->id)
                ->with('success', 'Лекция завершена! Теперь пройдите тест.');
        }

        $nextLesson = Lesson::where('course_id', $course->id)
            ->active()
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first();

        if ($nextLesson) {
            return redirect()->route('lectures.show', [
                'courseSlug' => $courseSlug,
                'lessonId' => $nextLesson->id
            ]);
        }

        return redirect()->route('lectures.index', $courseSlug)
            ->with('success', 'Лекция завершена! Вы закончили все уроки.');

    }

    private function updateCourseProgress($user, $course)
    {
        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($userCourse) {
            $totalLessons = Lesson::where('course_id', $course->id)->active()->count();
            $completedLessons = UserLesson::where('user_id', $user->id)
                ->whereHas('lesson', function($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->where('completed', true)
                ->count();

            $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $userCourse->update([
                'progress' => $progress,
                'current_lesson' => $completedLessons,
                ...(($completedLessons >= $totalLessons && $totalLessons > 0) ? ['completed_at' => now()] : [])
            ]);
        }
    }
    public function next($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$userCourse) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'Вы не записаны на этот курс');
        }

        $lessons = Lesson::where('course_id', $course->id)
            ->active()
            ->orderBy('order')
            ->get();

        $completedLessons = UserLesson::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->where('completed', true)
            ->get()
            ->keyBy('lesson_id');

        foreach ($lessons as $lesson) {
            if (!isset($completedLessons[$lesson->id])) {
                // Урок не завершён – проверяем тест
                $linkedTest = Test::where('lesson_id', $lesson->id)
                    ->where('is_active', true)
                    ->first();

                if ($linkedTest) {
                    $testPassed = TestResult::where('user_id', $user->id)
                        ->where('test_id', $linkedTest->id)
                        ->where('passed', true)
                        ->exists();

                    if ($testPassed) {
                        continue; // тест сдан → идём дальше
                    }

                    return redirect()->route('universal-tests.show', $linkedTest->id);
                }

                // Теста нет → идём на урок
                return redirect()->route('lectures.show', [
                    'courseSlug' => $courseSlug,
                    'lessonId' => $lesson->id
                ]);
            }
        }

        return redirect()->route('lectures.index', $courseSlug)
            ->with('success', 'Вы завершили все уроки курса!');
    }

    // Заглушка для старых вызовов complete-lesson
    public function completeFromOldRoute(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        return $this->complete($request, $lesson->course->slug, $lessonId);
    }

    private function isLessonAvailable($lesson, $allLessons, $userLessons)
    {
        $currentLessonOrder = $allLessons->search(function($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        if ($currentLessonOrder === 0) {
            return true;
        }

        for ($i = 0; $i < $currentLessonOrder; $i++) {
            $prevLesson = $allLessons[$i];
            $prevUserLesson = $userLessons[$prevLesson->id] ?? null;

            if (!$prevUserLesson || !$prevUserLesson->completed) {
                return false;
            }
        }

        return true;
    }

    private function getNextAvailableLesson($lessons, $userLessons)
    {
        foreach ($lessons as $lesson) {
            $userLesson = $userLessons[$lesson->id] ?? null;
            if (!$userLesson || !$userLesson->completed) {
                return $lesson;
            }
        }
        return null;
    }

    private function getNextLesson($lessons, $currentLessonId)
    {
        $currentIndex = $lessons->search(function($lesson) use ($currentLessonId) {
            return $lesson->id == $currentLessonId;
        });

        if ($currentIndex !== false && isset($lessons[$currentIndex + 1])) {
            return $lessons[$currentIndex + 1];
        }

        return null;
    }

    private function getPrevLesson($lessons, $currentLessonId)
    {
        $currentIndex = $lessons->search(function($lesson) use ($currentLessonId) {
            return $lesson->id == $currentLessonId;
        });

        if ($currentIndex !== false && $currentIndex > 0) {
            return $lessons[$currentIndex - 1];
        }

        return null;
    }
    public function roadmap($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$userCourse) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'Вы не записаны на этот курс');
        }

        $lessons = Lesson::where('course_id', $course->id)
            ->where('status', 'published')
            ->orderBy('order')
            ->get();

        $tests = Test::where('course_id', $course->id)
            ->where('is_active', true)
            ->where('type', '!=', 'placement')
            ->orderBy('order_index')
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
            ->map(fn($results) => $results->sortByDesc('score')->first());

        $roadmapItems = collect();
        $testsByLesson = $tests->groupBy('lesson_id');

        foreach ($lessons as $lesson) {
            $completed = isset($userLessons[$lesson->id]) && $userLessons[$lesson->id]->completed;
            $roadmapItems->push([
                'type' => 'lesson',
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'item' => $lesson,
                'is_completed' => $completed,
                'is_available' => true,
                'order' => $lesson->order,
            ]);

            foreach (($testsByLesson->get($lesson->id) ?? collect()) as $test) {
                $best = $userTests->get($test->id);
                $testCompleted = $best && $best->passed;
                $roadmapItems->push([
                    'type' => 'test',
                    'id' => $test->id,
                    'title' => $test->title,
                    'description' => $test->description,
                    'item' => $test,
                    'is_completed' => $testCompleted,
                    'is_available' => $completed,
                    'order' => $lesson->order . '.' . $test->order_index,
                    'linked_lesson_id' => $lesson->id,
                    'user_progress' => $best,
                ]);
            }
        }

        foreach ($tests->whereNull('lesson_id') as $test) {
            $best = $userTests->get($test->id);
            $testCompleted = $best && $best->passed;
            $roadmapItems->push([
                'type' => 'test',
                'id' => $test->id,
                'title' => $test->title,
                'description' => $test->description,
                'item' => $test,
                'is_completed' => $testCompleted,
                'is_available' => true,
                'order' => $test->order_index,
                'linked_lesson_id' => null,
                'user_progress' => $best,
            ]);
        }

        $roadmapItems = $roadmapItems->sortBy('order')->values();
        $previousCompleted = true;
        foreach ($roadmapItems as $key => &$item) {
            if ($key === 0) {
                $item['is_available'] = true;
            } else {
                $item['is_available'] = $previousCompleted;
            }
            if ($item['type'] === 'test' && !empty($item['linked_lesson_id'])) {
                $lessonCompleted = isset($userLessons[$item['linked_lesson_id']]) && $userLessons[$item['linked_lesson_id']]->completed;
                $item['is_available'] = $item['is_available'] && $lessonCompleted;
            }
            $previousCompleted = $item['is_completed'] ?? false;
        }

        return view('lectures.roadmap', compact('course', 'roadmapItems'));
    }
}
