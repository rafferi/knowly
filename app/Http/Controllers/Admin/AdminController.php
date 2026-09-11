<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Module;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Основная статистика
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'total_lessons' => Lesson::count(),
            'total_tests' => Test::count(),
            'total_modules' => Module::count(),
            'revenue' => Enrollment::where('payment_status', 'paid')->sum('price'),
            'avg_test_score' => TestResult::avg('score') ?? 0,
        ];

        // Статистика за последние 30 дней
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $stats['new_users_30d'] = User::where('created_at', '>=', $thirtyDaysAgo)->count();
        $stats['new_enrollments_30d'] = Enrollment::where('created_at', '>=', $thirtyDaysAgo)->count();
        $stats['revenue_30d'] = Enrollment::where('payment_status', 'paid')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('price');

        // Последние пользователи
        $recentUsers = User::latest()->take(5)->get();

        // Последние записи
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(5)
            ->get();

        // Популярные курсы (по количеству записей)
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentEnrollments', 'popularCourses'));
    }
}
