<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with(['course', 'module'])->paginate(20);
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        $courses = Course::all();
        $modules = Module::all();
        $lessons = Lesson::all();
        return view('admin.tests.create', compact('courses', 'modules', 'lessons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required'
        ]);

        Test::create($request->all());
        return redirect()->route('admin.tests')->with('success', 'Тест создан');
    }

    public function edit(Test $test)
    {
        $courses = Course::all();
        $modules = Module::all();
        $lessons = Lesson::all();
        return view('admin.tests.edit', compact('test', 'courses', 'modules', 'lessons'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'title' => 'required',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required'
        ]);

        $test->update($request->all());
        return redirect()->route('admin.tests')->with('success', 'Тест обновлен');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests')->with('success', 'Тест удален');
    }
}
