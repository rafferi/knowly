<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Homework::where('user_id', $user->id)
            ->with(['lesson', 'course', 'module'])
            ->orderBy('created_at', 'desc');

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $homeworks = $query->paginate(15);
        $stats = $this->getHomeworkStats($user);

        return view('homeworks.index', compact('homeworks', 'filter', 'stats'));
    }

    public function create($lessonId)
    {
        $lesson = Lesson::with(['course', 'module'])->findOrFail($lessonId);

        $existingHomework = Homework::where('user_id', Auth::id())
            ->where('lesson_id', $lessonId)
            ->first();

        if ($existingHomework) {
            return redirect()->route('homeworks.show', $existingHomework)
                ->with('info', 'Вы уже отправили домашнее задание для этого урока');
        }

        return view('homeworks.create', compact('lesson'));
    }

    public function store(Request $request, $lessonId)
    {
        $request->validate([
            'content' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,png,mp3,mp4'
        ]);

        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $user = Auth::user();

        $homework = Homework::create([
            'user_id' => $user->id,
            'lesson_id' => $lessonId,
            'course_id' => $lesson->course_id,
            'module_id' => $lesson->module_id,
            'content' => $request->content,
            'status' => 'submitted',
            'submitted_at' => now(),
            'started_at' => now(),
            'deadline_at' => $lesson->has_homework && $lesson->homework_instructions
                ? now()->addDays($lesson->homework_instructions['deadline_days'] ?? 7)
                : now()->addDays(7)
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('homeworks/' . $homework->id, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
            $homework->attachments = $attachments;
            $homework->save();
        }

        $this->updateUserHomeworkStats($user);

        return redirect()->route('homeworks.show', $homework)
            ->with('success', 'Домашнее задание успешно отправлено!');
    }

    public function show(Homework $homework)
    {
        if ($homework->user_id !== Auth::id() && !Auth::user()->isTeacher()) {
            abort(403);
        }

        $homework->load(['lesson', 'course', 'module', 'user']);

        return view('homeworks.show', compact('homework'));
    }

    public function edit(Homework $homework)
    {
        if ($homework->user_id !== Auth::id()) {
            abort(403);
        }

        if ($homework->status !== 'submitted' && $homework->status !== 'returned_for_revision') {
            return redirect()->route('homeworks.show', $homework)
                ->with('error', 'Нельзя редактировать проверенное задание');
        }

        return view('homeworks.edit', compact('homework'));
    }

    public function update(Request $request, Homework $homework)
    {
        if ($homework->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,png,mp3,mp4'
        ]);

        $homework->update([
            'content' => $request->content,
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = $homework->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('homeworks/' . $homework->id, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
            $homework->attachments = $attachments;
            $homework->save();
        }

        return redirect()->route('homeworks.show', $homework)
            ->with('success', 'Домашнее задание обновлено!');
    }

    public function destroy(Homework $homework)
    {
        if ($homework->user_id !== Auth::id()) {
            abort(403);
        }

        if ($homework->attachments) {
            foreach ($homework->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $homework->delete();

        return redirect()->route('homeworks.index')
            ->with('success', 'Домашнее задание удалено!');
    }

    public function grade(Request $request, Homework $homework)
    {
        if (!Auth::user()->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:' . $homework->max_score,
            'teacher_feedback' => 'required|string|min:10',
            'corrections' => 'nullable|array'
        ]);

        $homework->update([
            'score' => $request->score,
            'teacher_feedback' => $request->teacher_feedback,
            'corrections' => $request->corrections,
            'status' => 'graded',
            'reviewed_at' => now()
        ]);

        $this->updateUserHomeworkStats($homework->user);

        return redirect()->route('homeworks.show', $homework)
            ->with('success', 'Домашнее задание оценено!');
    }

    public function returnForRevision(Request $request, Homework $homework)
    {
        if (!Auth::user()->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'feedback' => 'required|string|min:10'
        ]);

        $homework->update([
            'teacher_feedback' => $request->feedback,
            'status' => 'returned_for_revision'
        ]);

        return redirect()->route('homeworks.show', $homework)
            ->with('success', 'Задание возвращено на доработку!');
    }

    private function getHomeworkStats(User $user)
    {
        return [
            'total' => Homework::where('user_id', $user->id)->count(),
            'submitted' => Homework::where('user_id', $user->id)->where('status', 'submitted')->count(),
            'graded' => Homework::where('user_id', $user->id)->where('status', 'graded')->count(),
            'average_score' => Homework::where('user_id', $user->id)->whereNotNull('score')->avg('score') ?? 0
        ];
    }

    private function updateUserHomeworkStats(User $user)
    {
        $progress = $user->userLearningProgress()->where('course_id', request()->course_id)->first();
        if ($progress) {
            $submittedCount = Homework::where('user_id', $user->id)
                ->where('course_id', request()->course_id)
                ->whereIn('status', ['submitted', 'graded'])
                ->count();

            $progress->update(['homeworks_submitted' => $submittedCount]);
        }
    }
}
