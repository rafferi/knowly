<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Показать страницу курсов
     */
    public function index()
    {
        return view('courses');
    }

    /**
     * Показать страницу конкретного курса
     */
    public function show($courseSlug)
    {

        return view('course-single', ['courseSlug' => $courseSlug]);
    }
}
