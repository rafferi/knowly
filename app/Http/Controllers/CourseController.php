<?php
// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        // Убираем with('category') пока нет категорий
        $courses = Course::active();

        if ($filter !== 'all') {
            $courses->where('course_type', $filter);
        }

        $courses = $courses->get();

        return view('courses', compact('courses', 'filter'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->firstOrFail();

        return view('course-single', compact('course'));
    }
}
