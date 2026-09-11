@extends('admin.layout')

@section('title', 'Пользователи')
@section('page-title', 'Пользователи')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Список пользователей</h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Добавить пользователя</a>
        </div>

        <!-- Фильтры и поиск -->
        <form method="GET" action="{{ route('admin.users') }}" class="filters-form">
            <div class="filter-row">
                <div class="filter-group">
                    <input type="text"
                           name="search"
                           placeholder="Поиск по имени или email..."
                           value="{{ request('search') }}"
                           class="filter-input">
                </div>

                <div class="filter-group">
                    <select name="role" class="filter-select" onchange="this.form.submit()">
                        <option value="">Все роли</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <select name="level" class="filter-select" onchange="this.form.submit()">
                        <option value="">Все уровни</option>
                        @foreach($levels as $level)
                            <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">Поиск</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline">Сбросить</a>
                </div>
            </div>
        </form>

        <!-- Таблица -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>
                        <a href="{{ route('admin.users', array_merge(request()->all(), ['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">
                            Имя
                            @if(request('sort') == 'name')
                                <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </a>
                    </th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Уровень</th>
                    <th>XP</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%;">
                                @else
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Админ</span>
                            @elseif($user->role == 'teacher')
                                <span class="badge bg-info">Преподаватель</span>
                            @else
                                <span class="badge bg-success">Студент</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $user->level }}</span>
                        </td>
                        <td>
                            <div class="xp-bar">
                                <div class="xp-progress" style="width: {{ min(100, ($user->xp / 1000) * 100) }}%"></div>
                                <span>{{ $user->xp }}</span>
                            </div>
                        </td>
                        <td>{{ $user->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-info" title="Просмотр">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')" title="Удалить">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <div class="pagination">
            {{ $users->appends(request()->query())->links() }}

            <div class="pagination-info">
                Показано {{ $users->firstItem() }} - {{ $users->lastItem() }} из {{ $users->total() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .filters-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-input, .filter-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tr:hover {
            background: #f9f9f9;
        }

        .xp-bar {
            position: relative;
            width: 80px;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .xp-progress {
            position: absolute;
            height: 100%;
            background: #28a745;
            border-radius: 10px;
        }

        .xp-bar span {
            position: absolute;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #333;
            line-height: 20px;
            z-index: 1;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 8px;
            font-size: 12px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 15px 0;
        }

        .pagination-info {
            color: #666;
            font-size: 14px;
        }
    </style>
@endpush
