<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $user->load([
            'activeCourses.course',
            'completedCourses.course',
            'userLessons' => function($query) {
                $query->orderBy('completed_at', 'desc')->limit(10);
            },
            'placementTests' => function($query) {
                $query->orderBy('completed_at', 'desc')->limit(1);
            }
        ]);

        return view('dashboard', compact('user'));
    }

    public function getWeeklyProgress()
    {
        $user = Auth::user();

        $progress = [
            'labels' => ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            'data' => [30, 45, 60, 35, 70, 50, 40]
        ];

        return response()->json($progress);
    }
}
