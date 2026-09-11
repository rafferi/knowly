<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])->paginate(20);
        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $users = User::all();
        $courses = Course::all();
        return view('admin.enrollments.create', compact('users', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required'
        ]);

        Enrollment::create($request->all());
        return redirect()->route('admin.enrollments')->with('success', 'Запись создана');
    }

    public function edit(Enrollment $enrollment)
    {
        $users = User::all();
        $courses = Course::all();
        return view('admin.enrollments.edit', compact('enrollment', 'users', 'courses'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required'
        ]);

        $enrollment->update($request->all());
        return redirect()->route('admin.enrollments')->with('success', 'Запись обновлена');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('admin.enrollments')->with('success', 'Запись удалена');
    }
}
