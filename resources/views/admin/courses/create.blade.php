@extends('admin.layout')

@section('title', 'Создать курс')
@section('page-title', 'Создать курс')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Новый курс</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Название</label>
                    <input type="text" name="title" value="{{ old('title') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Описание</label>
                    <textarea name="description" rows="4" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ old('description') }}</textarea>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Цена</label>
                    <input type="number" name="price" value="{{ old('price') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Уровень</label>
                    <select name="level" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="beginner">Начинающий</option>
                        <option value="elementary">Элементарный</option>
                        <option value="intermediate">Средний</option>
                        <option value="upper_intermediate">Выше среднего</option>
                        <option value="advanced">Продвинутый</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.courses') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
