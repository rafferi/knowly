@extends('admin.layout')

@section('title', 'Редактировать тест')
@section('page-title', 'Редактировать тест')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Редактировать тест</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.tests.update', $test) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Название</label>
                    <input type="text" name="title" value="{{ $test->title }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Курс</label>
                    <select name="course_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $test->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Тип</label>
                    <select name="type" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="placement" {{ $test->type == 'placement' ? 'selected' : '' }}>Placement Test</option>
                        <option value="module_test" {{ $test->type == 'module_test' ? 'selected' : '' }}>Module Test</option>
                        <option value="weekly_test" {{ $test->type == 'weekly_test' ? 'selected' : '' }}>Weekly Test</option>
                        <option value="midterm" {{ $test->type == 'midterm' ? 'selected' : '' }}>Midterm</option>
                        <option value="final" {{ $test->type == 'final' ? 'selected' : '' }}>Final Test</option>
                        <option value="practice" {{ $test->type == 'practice' ? 'selected' : '' }}>Practice Test</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Минимальный балл</label>
                    <input type="number" name="passing_score" value="{{ $test->passing_score }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Максимум попыток</label>
                    <input type="number" name="max_attempts" value="{{ $test->max_attempts }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.tests') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
