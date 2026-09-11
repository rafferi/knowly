<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\UserCourse;
use App\Models\UserLesson;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseSequenceController extends Controller
{
    public function showSequence($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$userCourse) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'Вы не записаны на этот курс');
        }

        $sequence = $this->getCourseSequence($course);

        $userLessons = UserLesson::where('user_id', $user->id)
            ->whereIn('lesson_id', $sequence->where('type', 'lesson')->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        $testResults = TestResult::where('user_id', $user->id)
            ->whereIn('test_id', $sequence->where('type', 'test')->pluck('id'))
            ->get()
            ->keyBy('test_id');

        return view('course.sequence', compact(
            'course',
            'sequence',
            'userLessons',
            'testResults',
            'userCourse'
        ));
    }

    public function showItem($courseSlug, $itemType, $itemId)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$userCourse) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'Вы не записаны на этот курс');
        }

        if ($itemType === 'lesson') {
            $item = Lesson::with(['module', 'course'])->findOrFail($itemId);

            if ($item->course_id !== $course->id) {
                abort(404, 'Урок не принадлежит этому курсу');
            }

            $sequence = $this->getCourseSequence($course);
            $currentIndex = $sequence->search(function ($sequenceItem) use ($itemId, $itemType) {
                return $sequenceItem['type'] === $itemType && $sequenceItem['id'] == $itemId;
            });

            $nextItem = ($currentIndex !== false && isset($sequence[$currentIndex + 1]))
                ? $sequence[$currentIndex + 1]
                : null;

            $prevItem = ($currentIndex !== false && isset($sequence[$currentIndex - 1]))
                ? $sequence[$currentIndex - 1]
                : null;

            $userLesson = UserLesson::where('user_id', $user->id)
                ->where('lesson_id', $itemId)
                ->first();

            return view('course.lesson-item', compact(
                'course',
                'item',
                'nextItem',
                'prevItem',
                'userLesson'
            ));
        } else {
            $item = Test::with(['course', 'module', 'questions'])->findOrFail($itemId);

            if ($item->course_id !== $course->id) {
                abort(404, 'Тест не принадлежит этому курсу');
            }

            $sequence = $this->getCourseSequence($course);
            $currentIndex = $sequence->search(function ($sequenceItem) use ($itemId, $itemType) {
                return $sequenceItem['type'] === $itemType && $sequenceItem['id'] == $itemId;
            });

            $nextItem = ($currentIndex !== false && isset($sequence[$currentIndex + 1]))
                ? $sequence[$currentIndex + 1]
                : null;

            $prevItem = ($currentIndex !== false && isset($sequence[$currentIndex - 1]))
                ? $sequence[$currentIndex - 1]
                : null;

            $attempts = TestResult::where('user_id', $user->id)
                ->where('test_id', $item->id)
                ->orderBy('attempt_number', 'desc')
                ->get();

            $canTakeTest = $attempts->count() < $item->max_attempts;
            $nextAttempt = $attempts->count() + 1;
            $bestResult = $attempts->sortByDesc('score')->first();

            return view('course.test-item', compact(
                'course',
                'item',
                'nextItem',
                'prevItem',
                'attempts',
                'canTakeTest',
                'nextAttempt',
                'bestResult'
            ));
        }
    }

    private function getCourseSequence(Course $course)
    {
        // Получаем опубликованные уроки без сортировки по week_number
        $lessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('order')
            ->get();

        // Получаем активные тесты без сортировки по week_number
        $tests = $course->tests()
            ->where('is_active', true)
            ->where('type', '!=', 'placement')
            ->orderBy('order_index')
            ->get();

        $sequence = collect();

        // Добавляем все уроки в порядке их порядка
        foreach ($lessons as $lesson) {
            $sequence->push([
                'type' => 'lesson',
                'id' => $lesson->id,
                'title' => $lesson->title,
                'order' => $lesson->order,
                'item' => $lesson
            ]);

            // Добавляем тесты, связанные с этим уроком
            $lessonTests = $tests->where('lesson_id', $lesson->id);
            foreach ($lessonTests as $test) {
                $sequence->push([
                    'type' => 'test',
                    'id' => $test->id,
                    'title' => $test->title,
                    'order_index' => $test->order_index,
                    'item' => $test
                ]);
            }
        }

        // Добавляем тесты модуля (без привязки к уроку)
        $moduleTests = $tests->whereNotNull('module_id')->whereNull('lesson_id');
        foreach ($moduleTests as $test) {
            $sequence->push([
                'type' => 'test',
                'id' => $test->id,
                'title' => $test->title,
                'order_index' => $test->order_index,
                'item' => $test
            ]);
        }

        // Сортируем всю последовательность по порядку
        return $sequence->sortBy(function($item) {
            return $item['type'] === 'lesson' ? $item['order'] : $item['order_index'];
        })->values();
    }

    public function startCourse($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $userCourse = UserCourse::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'progress' => 0,
                'current_lesson' => 0,
                'started_at' => now()
            ]
        );

        $sequence = $this->getCourseSequence($course);

        if ($sequence->isEmpty()) {
            return redirect()->route('course.sequence', $courseSlug)
                ->with('error', 'В этом курсе пока нет материалов');
        }

        $firstItem = $sequence->first();

        return redirect()->route('course.sequence.item', [
            'course' => $courseSlug,
            'itemType' => $firstItem['type'],
            'itemId' => $firstItem['id']
        ]);
    }

    public function completeLesson(Request $request, $courseSlug, $lessonId)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $lesson = Lesson::findOrFail($lessonId);

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $userLesson = UserLesson::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lessonId],
            [
                'completed' => true,
                'completed_at' => now(),
                'time_spent' => $request->input('time_spent', 300),
                'score' => $request->input('score', 100),
                'module_id' => $lesson->module_id
            ]
        );

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($userCourse) {
            $totalLessons = $course->lessons()->where('is_published', true)->count();
            $completedLessons = UserLesson::where('user_id', $user->id)
                ->whereHas('lesson', function($query) use ($course) {
                    $query->where('course_id', $course->id)
                        ->where('is_published', true);
                })
                ->where('completed', true)
                ->count();

            $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $userCourse->update([
                'progress' => $progress,
                'current_lesson' => $completedLessons
            ]);
        }

        $sequence = $this->getCourseSequence($course);
        $currentIndex = $sequence->search(function ($sequenceItem) use ($lessonId) {
            return $sequenceItem['type'] === 'lesson' && $sequenceItem['id'] == $lessonId;
        });

        if ($currentIndex !== false && isset($sequence[$currentIndex + 1])) {
            $nextItem = $sequence[$currentIndex + 1];
            return redirect()->route('course.sequence.item', [
                'course' => $courseSlug,
                'itemType' => $nextItem['type'],
                'itemId' => $nextItem['id']
            ]);
        }

        return redirect()->route('course.sequence', $courseSlug)
            ->with('success', 'Урок завершен! Вы закончили все материалы курса.');
    }
}
