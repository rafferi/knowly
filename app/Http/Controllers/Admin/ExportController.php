<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportUsers()
    {
        $users = User::all();

        $callback = function() use ($users) {
            // Открываем output
            $file = fopen('php://output', 'w');

            // Добавляем BOM для UTF-8 (важно для Excel)
            fwrite($file, "\xEF\xBB\xBF");

            // Заголовки CSV
            fputcsv($file, ['ID', 'Имя', 'Email', 'Роль', 'Уровень', 'XP', 'Телефон', 'Дата регистрации'], ';');

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->level,
                    $user->xp,
                    $user->phone ?? '',
                    $user->created_at->format('Y-m-d')
                ], ';');
            }

            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    public function exportCourses()
    {
        $courses = Course::with('category')->get();

        $callback = function() use ($courses) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Название', 'Категория', 'Цена', 'Уроков', 'Уровень', 'Статус', 'Дата создания'], ';');

            foreach ($courses as $course) {
                fputcsv($file, [
                    $course->id,
                    $course->title,
                    $course->category ? $course->category->name : 'Без категории',
                    $course->price,
                    $course->lessons_count,
                    $course->level,
                    $course->is_active ? 'Активен' : 'Не активен',
                    $course->created_at->format('Y-m-d')
                ], ';');
            }

            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="courses_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    public function exportEnrollments()
    {
        $enrollments = Enrollment::with(['user', 'course'])->get();

        $callback = function() use ($enrollments) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Пользователь', 'Email', 'Курс', 'Статус', 'Статус оплаты', 'Цена', 'Дата записи'], ';');

            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->id,
                    $enrollment->user->name,
                    $enrollment->user->email,
                    $enrollment->course->title,
                    $enrollment->status,
                    $enrollment->payment_status,
                    $enrollment->price ?? 0,
                    $enrollment->created_at->format('Y-m-d')
                ], ';');
            }

            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="enrollments_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    // Добавленный метод для экспорта финансового отчета
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
