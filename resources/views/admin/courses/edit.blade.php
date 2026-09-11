@extends('admin.layout')

@section('title', 'Редактировать курс')
@section('page-title', 'Редактировать курс')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Редактировать курс</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Название</label>
                    <input type="text" name="title" value="{{ $course->title }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Slug</label>
                    <input type="text" name="slug" value="{{ $course->slug }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Описание</label>
                    <textarea name="description" rows="4" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ $course->description }}</textarea>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Цена</label>
                    <input type="number" name="price" value="{{ $course->price }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Уровень</label>
                    <select name="level" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Начинающий</option>
                        <option value="elementary" {{ $course->level == 'elementary' ? 'selected' : '' }}>Элементарный</option>
                        <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Средний</option>
                        <option value="upper_intermediate" {{ $course->level == 'upper_intermediate' ? 'selected' : '' }}>Выше среднего</option>
                        <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Продвинутый</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Количество уроков</label>
                    <input type="number" name="lessons_count" value="{{ $course->lessons_count }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.courses') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
