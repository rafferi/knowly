<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $courses = Course::active();

        if ($filter !== 'all') {
            $courses->where('course_type', $filter);
        }

        $courses = $courses->get();

        // Декодируем features для каждого курса
        foreach ($courses as $course) {
            $course = $this->processCourseFeatures($course);
        }

        return view('courses', compact('courses', 'filter'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Декодируем features для курса
        $course = $this->processCourseFeatures($course);

        return view('course-single', compact('course'));
    }

    /**
     * Обрабатывает поле features курса
     * Преобразует строку JSON в массив, если нужно
     * Гарантирует, что features всегда будет массивом
     */
    private function processCourseFeatures(Course $course)
    {
        // Если features уже является массивом, возвращаем как есть
        if (is_array($course->features)) {
            return $course;
        }

        // Если features является строкой, пробуем декодировать JSON
        if (is_string($course->features)) {
            $decoded = json_decode($course->features, true);

            // Если декодирование успешно, сохраняем массив
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $course->features = $decoded;
            } else {
                // Если декодирование не удалось, используем пустой массив
                $course->features = [];
            }
        } else {
            // Если features null или другой тип, используем пустой массив
            $course->features = [];
        }

        // Если все еще пустой массив, добавляем дефолтные фичи
        if (empty($course->features)) {
            $course->features = $this->getDefaultFeatures($course);
        }

        return $course;
    }

    /**
     * Получить дефолтные фичи для курса
     */
    private function getDefaultFeatures(Course $course)
    {
        $defaultFeatures = [
            'business' => [
                'Деловые переговоры на английском',
                'Бизнес-переписка',
                'Подготовка к собеседованию',
                'Международные контракты',
                'Корпоративная лексика'
            ],
            'conversational' => [
                'Живое общение с носителями',
                'Разговорные клубы',
                'Просмотр фильмов и сериалов',
                'Обсуждение актуальных тем',
                'Коррекция произношения'
            ],
            'exam' => [
                'Подготовка к IELTS/TOEFL',
                'Пробные тесты',
                'Стратегии сдачи экзамена',
                'Тайм-менеджмент на экзамене',
                'Разбор типичных ошибок'
            ],
            'default' => [
                'Бесплатный пробный урок',
                'Персональный преподаватель',
                'Интерактивные материалы',
                'Мобильное приложение',
                'Сертификат об окончании'
            ]
        ];

        $courseType = $course->course_type ?? 'default';
        return $defaultFeatures[$courseType] ?? $defaultFeatures['default'];
    }
}
