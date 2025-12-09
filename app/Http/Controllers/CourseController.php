<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $courses = Course::active();

        if ($filter !== 'all') {
            $courses->where('course_type', $filter);
        }

        $courses = $courses->get();

        // Декодируем features для каждого курса
        foreach ($courses as $course) {
            if (is_string($course->features)) {
                $course->features = json_decode($course->features, true);
            }
        }

        return view('courses', compact('courses', 'filter'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Декодируем features для курса
        if (is_string($course->features)) {
            $course->features = json_decode($course->features, true);
        }

        return view('course-single', compact('course'));
    }
}
