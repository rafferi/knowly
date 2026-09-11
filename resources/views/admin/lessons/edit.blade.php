@extends('admin.layout')

@section('title', 'Редактировать урок')
@section('page-title', 'Редактировать урок')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Редактировать урок</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Название</label>
                    <input type="text" name="title" value="{{ $lesson->title }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Курс</label>
                    <select name="course_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $lesson->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Модуль</label>
                    <select name="module_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="">Без модуля</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ $lesson->module_id == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Порядок</label>
                    <input type="number" name="order" value="{{ $lesson->order }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Содержание</label>
                    <textarea name="content" rows="10" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ $lesson->content }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.lessons') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
