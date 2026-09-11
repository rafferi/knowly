@extends('admin.layout')

@section('title', 'Создать тест')
@section('page-title', 'Создать тест')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Новый тест</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.tests.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Название</label>
                    <input type="text" name="title" value="{{ old('title') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Курс</label>
                    <select name="course_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Тип</label>
                    <select name="type" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="placement">Placement Test</option>
                        <option value="module_test">Module Test</option>
                        <option value="weekly_test">Weekly Test</option>
                        <option value="midterm">Midterm</option>
                        <option value="final">Final Test</option>
                        <option value="practice">Practice Test</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Минимальный балл</label>
                    <input type="number" name="passing_score" value="{{ old('passing_score', 70) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Максимум попыток</label>
                    <input type="number" name="max_attempts" value="{{ old('max_attempts', 3) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.tests') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
