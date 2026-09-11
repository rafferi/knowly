<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with(['course', 'module'])->paginate(20);
        return view('admin.lessons.index', compact('lessons'));
    }

    public function create()
    {
        $courses = Course::all();
        $modules = Module::all();
        return view('admin.lessons.create', compact('courses', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'course_id' => 'required|exists:courses,id',
            'content' => 'required'
        ]);

        Lesson::create($request->all());
        return redirect()->route('admin.lessons')->with('success', 'Урок создан');
    }

    public function edit(Lesson $lesson)
    {
        $courses = Course::all();
        $modules = Module::all();
        return view('admin.lessons.edit', compact('lesson', 'courses', 'modules'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required',
            'course_id' => 'required|exists:courses,id',
            'content' => 'required'
        ]);

        $lesson->update($request->all());
        return redirect()->route('admin.lessons')->with('success', 'Урок обновлен');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('admin.lessons')->with('success', 'Урок удален');
    }
}
