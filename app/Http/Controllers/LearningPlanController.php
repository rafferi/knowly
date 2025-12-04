<?php

namespace App\Http\Controllers;

use App\Models\{Course, Module, Lesson, UserLearningProgress};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningPlanController extends Controller
{

    public function show($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)
            ->with(['modules' => function($query) {
                $query->orderBy('order_index');
            }, 'modules.lessons' => function($query) {
                $query->orderBy('week_number')->orderBy('order');
            }])
            ->firstOrFail();

        $user = Auth::user();
        $progress = null;

        if ($user) {
            $progress = UserLearningProgress::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();
        }

        return view('learning.plan', compact('course', 'progress'));
    }


    public function showWeek($courseSlug, $week)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $lessons = Lesson::where('course_id', $course->id)
            ->where('week_number', $week)
            ->orderBy('order')
            ->with('module')
            ->get();

        $module = Module::where('course_id', $course->id)
            ->where('start_week', '<=', $week)
            ->where('end_week', '>=', $week)
            ->first();

        $user = Auth::user();
        $userLessons = collect();

        if ($user) {
            $userLessons = $user->userLessons()
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->get()
                ->keyBy('lesson_id');
        }

        return view('learning.week', compact(
            'course',
            'week',
            'lessons',
            'module',
            'userLessons'
        ));
    }


    public function showLesson($courseSlug, $lessonId)
    {
        $lesson = Lesson::with(['course', 'module'])
            ->whereHas('course', function($query) use ($courseSlug) {
                $query->where('slug', $courseSlug);
            })
            ->findOrFail($lessonId);

        $user = Auth::user();
        $userLesson = null;
        $nextLesson = null;
        $prevLesson = null;

        if ($user) {
            $userLesson = $user->userLessons()
                ->where('lesson_id', $lessonId)
                ->first();


            $nextLesson = Lesson::where('course_id', $lesson->course_id)
                ->where('week_number', $lesson->week_number)
                ->where('order', '>', $lesson->order)
                ->orderBy('order')
                ->first();

            if (!$nextLesson) {
                $nextLesson = Lesson::where('course_id', $lesson->course_id)
                    ->where('week_number', '>', $lesson->week_number)
                    ->orderBy('week_number')
                    ->orderBy('order')
                    ->first();
            }


            $prevLesson = Lesson::where('course_id', $lesson->course_id)
                ->where('week_number', $lesson->week_number)
                ->where('order', '<', $lesson->order)
                ->orderBy('order', 'desc')
                ->first();

            if (!$prevLesson) {
                $prevLesson = Lesson::where('course_id', $lesson->course_id)
                    ->where('week_number', '<', $lesson->week_number)
                    ->orderBy('week_number', 'desc')
                    ->orderBy('order', 'desc')
                    ->first();
            }
        }

        return view('learning.lesson', compact(
            'lesson',
            'userLesson',
            'nextLesson',
            'prevLesson'
        ));
    }


    public function startCourse(Request $request, $courseId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $course = Course::findOrFail($courseId);

        $progress = UserLearningProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $courseId],
            [
                'total_weeks' => $course->duration * 4,
                'current_week' => 1,
                'start_date' => now(),
                'estimated_completion' => now()->addWeeks($course->duration * 4),
                'weekly_goal_hours' => $request->input('weekly_hours', 5)
            ]
        );

        return redirect()->route('learning.plan', ['course' => $course->slug])
            ->with('success', 'Обучение начато! Удачи!');
    }


    public function completeLesson(Request $request, $lessonId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Требуется авторизация'], 401);
        }

        $lesson = Lesson::findOrFail($lessonId);

        $userLesson = $user->userLessons()->updateOrCreate(
            ['lesson_id' => $lessonId],
            [
                'completed' => true,
                'completed_at' => now(),
                'time_spent' => $request->input('time_spent', $lesson->estimated_time),
                'score' => $request->input('score', 100),
                'week_number' => $lesson->week_number,
                'module_id' => $lesson->module_id
            ]
        );

        $progress = UserLearningProgress::where('user_id', $user->id)
            ->where('course_id', $lesson->course_id)
            ->first();

        if ($progress) {

            $totalLessons = Lesson::where('course_id', $lesson->course_id)->count();
            $completedLessons = $user->userLessons()
                ->whereHas('lesson', function($query) use ($lesson) {
                    $query->where('course_id', $lesson->course_id);
                })
                ->where('completed', true)
                ->count();

            $progress->update([
                'overall_progress' => ($completedLessons / $totalLessons) * 100,
                'lessons_completed' => $completedLessons,
                'last_study_date' => now(),
                'total_study_time' => $progress->total_study_time + $request->input('time_spent', $lesson->estimated_time)
            ]);


            $weekLessons = Lesson::where('course_id', $lesson->course_id)
                ->where('week_number', $lesson->week_number)
                ->count();

            $completedWeekLessons = $user->userLessons()
                ->whereHas('lesson', function($query) use ($lesson) {
                    $query->where('course_id', $lesson->course_id)
                        ->where('week_number', $lesson->week_number);
                })
                ->where('completed', true)
                ->count();

            if ($weekLessons > 0 && $completedWeekLessons >= $weekLessons) {
                $completedWeeks = $progress->completed_weeks ?? [];
                if (!in_array($lesson->week_number, $completedWeeks)) {
                    $completedWeeks[] = $lesson->week_number;
                    $progress->completed_weeks = $completedWeeks;


                    if ($lesson->week_number >= $progress->current_week) {
                        $progress->current_week = $lesson->week_number + 1;
                    }

                    $progress->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Урок отмечен как пройденный!',
            'progress' => $progress ? $progress->overall_progress : 0
        ]);
    }
}
