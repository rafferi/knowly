<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:courses',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        Course::create($request->all());
        return redirect()->route('admin.courses')->with('success', 'Курс создан');
    }

    public function edit(Course $course)
    {
        $categories = Category::all();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:courses,slug,' . $course->id,
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        $course->update($request->all());
        return redirect()->route('admin.courses')->with('success', 'Курс обновлен');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses')->with('success', 'Курс удален');
    }
}
