<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\TestResult;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function usersReport(Request $request)
    {
        $query = User::query();

        // Фильтры
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Статистика
        $stats = [
            'total' => $users->count(),
            'students' => $users->where('role', 'student')->count(),
            'teachers' => $users->where('role', 'teacher')->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'avg_xp' => round($users->avg('xp') ?? 0, 0),
        ];

        return view('admin.reports.users', compact('users', 'stats'));
    }

    public function enrollmentsReport(Request $request)
    {
        $query = Enrollment::with(['user', 'course']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $enrollments = $query->orderBy('created_at', 'desc')->get();

        // Статистика
        $stats = [
            'total' => $enrollments->count(),
            'active' => $enrollments->where('status', 'active')->count(),
            'completed' => $enrollments->where('status', 'completed')->count(),
            'pending' => $enrollments->where('status', 'pending')->count(),
            'total_revenue' => $enrollments->where('payment_status', 'paid')->sum('price') ?? 0,
        ];

        return view('admin.reports.enrollments', compact('enrollments', 'stats'));
    }

    public function revenueReport(Request $request)
    {
        $query = Enrollment::where('payment_status', 'paid')->with('course');

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $enrollments = $query->orderBy('created_at', 'desc')->get();

        // Группировка по месяцам для графика
        $monthlyRevenue = [];
        $currentYear = date('Y');

        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyRevenue[$i] = [
                'month' => $month,
                'year' => $currentYear,
                'revenue' => 0
            ];
        }

        foreach ($enrollments as $enrollment) {
            $month = $enrollment->created_at->format('n');
            $monthlyRevenue[$month]['revenue'] += $enrollment->price ?? 0;
        }

        $stats = [
            'total_revenue' => $enrollments->sum('price') ?? 0,
            'average_revenue' => $enrollments->avg('price') ?? 0,
            'count' => $enrollments->count(),
        ];

        return view('admin.reports.revenue', compact('enrollments', 'monthlyRevenue', 'stats'));
    }

    public function learningProgressReport(Request $request)
    {
        // Статистика по курсам с подсчетом записей и уроков
        $courses = Course::withCount(['enrollments', 'lessons'])
            ->orderBy('enrollments_count', 'desc')
            ->get();

        // Добавляем средний прогресс из таблицы user_courses
        foreach ($courses as $course) {
            $avgProgress = UserCourse::where('course_id', $course->id)->avg('progress');
            $course->avg_progress = $avgProgress ?? 0;
        }

        // Популярные курсы (первые 5)
        $popularCourses = $courses->take(5);

        // Прогресс пользователей - топ 10 по количеству пройденных уроков
        $usersProgress = User::withCount(['userCourses'])
            ->orderBy('total_lessons_completed', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.learning-progress', compact('courses', 'popularCourses', 'usersProgress'));
    }

    public function exportRevenue(Request $request)
    {
        $query = Enrollment::where('payment_status', 'paid')->with('course');

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $enrollments = $query->orderBy('created_at', 'desc')->get();

        $callback = function() use ($enrollments) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Пользователь', 'Email', 'Курс', 'Цена', 'Дата оплаты', 'Метод оплаты'], ';');

            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->id,
                    $enrollment->user->name,
                    $enrollment->user->email,
                    $enrollment->course->title,
                    $enrollment->price ?? 0,
                    $enrollment->created_at->format('Y-m-d'),
                    $enrollment->payment_method ?? 'Не указан'
                ], ';');
            }

            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="revenue_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }
}
