<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseRecommendationController extends Controller
{
    public function showTest()
    {
        return view('course-recommendation-test');
    }

    public function processTest(Request $request)
    {
        $answers = $request->input('answers');


        $recommendation = $this->calculateRecommendation($answers);


        session(['course_recommendation' => $recommendation]);

        return redirect()->route('course-recommendation.result');
    }

    public function showResult()
    {
        $recommendation = session('course_recommendation');

        if (!$recommendation) {
            return redirect()->route('course-recommendation.test');
        }

        return view('course-recommendation-result', compact('recommendation'));
    }

    private function calculateRecommendation($answers)
    {

        $scores = [
            'business' => 0,
            'it' => 0,
            'academic' => 0,
            'daily' => 0
        ];


        if (isset($answers['goal'])) {
            $scores[$answers['goal']] += 3;
        }

        if (isset($answers['field'])) {
            if ($answers['field'] === 'business_corp') $scores['business'] += 2;
            if ($answers['field'] === 'it_tech') $scores['it'] += 2;
            if ($answers['field'] === 'education') $scores['academic'] += 2;
            if ($answers['field'] === 'other') $scores['daily'] += 2;
        }

        if (isset($answers['skills'])) {
            if ($answers['skills'] === 'presentations') $scores['business'] += 2;
            if ($answers['skills'] === 'technical') $scores['it'] += 2;
            if ($answers['skills'] === 'academic_writing') $scores['academic'] += 2;
            if ($answers['skills'] === 'conversation') $scores['daily'] += 2;
        }

        if (isset($answers['topics'])) {
            if ($answers['topics'] === 'business_topics') $scores['business'] += 1;
            if ($answers['topics'] === 'tech_topics') $scores['it'] += 1;
            if ($answers['topics'] === 'academic_topics') $scores['academic'] += 1;
            if ($answers['topics'] === 'daily_topics') $scores['daily'] += 1;
        }


        $recommendedType = array_search(max($scores), $scores);


        return $this->getRecommendationData($recommendedType);
    }

    private function getRecommendationData($type)
    {
        $recommendations = [
            'business' => [
                'course_name' => 'Business English Pro',
                'description' => 'Идеально для карьерного роста и работы в международных компаниях. Фокус на деловую коммуникацию, переговоры и профессиональную переписку.',
                'intensity' => 'Высокая',
                'duration' => '3-6 месяцев',
                'format' => 'Индивидуально',
                'lessons_count' => '24-48',
                'price_range' => '18-25 тыс.₽/мес',
                'benefits' => [
                    'Деловая переписка на профессиональном уровне',
                    'Подготовка к международным собеседованиям',
                    'Навыки проведения презентаций на английском',
                    'Освоение бизнес-лексики и идиом',
                    'Персональный карьерный консультант'
                ]
            ],
            'it' => [
                'course_name' => 'IT English Specialist',
                'description' => 'Специализированный курс для IT-специалистов. Изучение технической терминологии, документации и коммуникации в IT-среде.',
                'intensity' => 'Средняя',
                'duration' => '4-8 месяцев',
                'format' => 'Групповые + индивидуальные',
                'lessons_count' => '32-64',
                'price_range' => '15-20 тыс.₽/мес',
                'benefits' => [
                    'Техническая документация и спецификации',
                    'Коммуникация с иностранными коллегами',
                    'Участие в международных IT-конференциях',
                    'Подготовка к техническим собеседованиям',
                    'IT-проекты с реальными кейсами'
                ]
            ],
            'academic' => [
                'course_name' => 'Academic English Excellence',
                'description' => 'Для учебы за границей и академических целей. Подготовка к международным экзаменам и академическое письмо.',
                'intensity' => 'Высокая',
                'duration' => '6-12 месяцев',
                'format' => 'Индивидуально',
                'lessons_count' => '48-96',
                'price_range' => '20-30 тыс.₽/мес',
                'benefits' => [
                    'Подготовка к IELTS/TOEFL',
                    'Академическое письмо и эссе',
                    'Исследовательские работы на английском',
                    'Участие в академических дискуссиях',
                    'Сертификат международного образца'
                ]
            ],
            'daily' => [
                'course_name' => 'Everyday Communication',
                'description' => 'Для повседневного общения и путешествий. Развитие разговорных навыков и понимания на слух в реальных ситуациях.',
                'intensity' => 'Легкая',
                'duration' => '2-4 месяца',
                'format' => 'Групповые занятия',
                'lessons_count' => '16-32',
                'price_range' => '8-12 тыс.₽/мес',
                'benefits' => [
                    'Свободное общение в путешествиях',
                    'Понимание фильмов и сериалов в оригинале',
                    'Повседневные диалоги и ситуации',
                    'Уверенность в разговорной речи',
                    'Интерактивные разговорные клубы'
                ]
            ]
        ];

        return $recommendations[$type] ?? $recommendations['daily'];
    }
}
