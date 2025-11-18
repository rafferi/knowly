<?php
// [file name]: PlacementQuestionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlacementQuestion;

class PlacementQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            // Beginner Level (A1)
            [
                'level' => 'beginner',
                'question' => 'Choose the correct word: I ___ a student.',
                'options' => json_encode(['am', 'is', 'are', 'be']), // Явно кодируем в JSON
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 1
            ],
            [
                'level' => 'beginner',
                'question' => "What's the opposite of 'hot'?",
                'options' => json_encode(['cold', 'warm', 'cool', 'freezing']),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 2
            ],
            [
                'level' => 'beginner',
                'question' => 'Complete the sentence: She ___ to school every day.',
                'options' => json_encode(['go', 'goes', 'going', 'went']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 3
            ],
            [
                'level' => 'beginner',
                'question' => 'Choose the correct plural: one book, two ___',
                'options' => json_encode(['book', 'books', 'bookes', 'bookies']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 4
            ],
            [
                'level' => 'beginner',
                'question' => 'What time is it? 7:30',
                'options' => json_encode(['Seven thirty', 'Half past seven', 'Both are correct', 'Neither']),
                'correct_index' => 2,
                'type' => 'multiple_choice',
                'order' => 5
            ],

            // Elementary Level (A2)
            [
                'level' => 'elementary',
                'question' => "Choose the correct preposition: I'm good ___ math.",
                'options' => json_encode(['at', 'in', 'on', 'for']),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 1
            ],
            [
                'level' => 'elementary',
                'question' => 'Which sentence is correct?',
                'options' => json_encode([
                    'She don\'t like coffee.',
                    'She doesn\'t likes coffee.',
                    'She doesn\'t like coffee.',
                    'She not like coffee.'
                ]),
                'correct_index' => 2,
                'type' => 'multiple_choice',
                'order' => 2
            ],
            [
                'level' => 'elementary',
                'question' => 'Complete with present continuous: They ___ TV right now.',
                'options' => json_encode(['watch', 'watches', 'are watching', 'watching']),
                'correct_index' => 2,
                'type' => 'multiple_choice',
                'order' => 3
            ],
            [
                'level' => 'elementary',
                'question' => 'Choose the correct comparative: This book is ___ than that one.',
                'options' => json_encode(['interesting', 'more interesting', 'interestinger', 'most interesting']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 4
            ],
            [
                'level' => 'elementary',
                'question' => 'Fill in the blank: How ___ milk do you need?',
                'options' => json_encode(['many', 'much', 'some', 'any']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 5
            ],

            // Intermediate Level (B1)
            [
                'level' => 'intermediate',
                'question' => 'Choose the correct conditional: If I ___ you, I would study more.',
                'options' => json_encode(['am', 'was', 'were', 'would be']),
                'correct_index' => 2,
                'type' => 'multiple_choice',
                'order' => 1
            ],
            [
                'level' => 'intermediate',
                'question' => 'Which sentence uses the present perfect correctly?',
                'options' => json_encode([
                    'I have seen that movie yesterday.',
                    'I saw that movie already.',
                    'I have already seen that movie.',
                    'I did see that movie.'
                ]),
                'correct_index' => 2,
                'type' => 'multiple_choice',
                'order' => 2
            ],
            [
                'level' => 'intermediate',
                'question' => 'Choose the correct phrasal verb: Please ___ the light when you leave.',
                'options' => json_encode(['turn off', 'turn out', 'turn down', 'turn over']),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 3
            ],
            [
                'level' => 'intermediate',
                'question' => 'Reported speech: "I will call you tomorrow." → He said ___',
                'options' => json_encode([
                    'he will call me tomorrow.',
                    'he would call me tomorrow.',
                    'he calls me tomorrow.',
                    'he called me tomorrow.'
                ]),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 4
            ],
            [
                'level' => 'intermediate',
                'question' => 'Choose the correct article: She is ___ honest person.',
                'options' => json_encode(['a', 'an', 'the', 'no article']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 5
            ],

            // Upper Intermediate Level (B2)
            [
                'level' => 'upper_intermediate',
                'question' => 'Choose the correct modal: You ___ have told me earlier!',
                'options' => json_encode(['should', 'would', 'could', 'might']),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 1
            ],
            [
                'level' => 'upper_intermediate',
                'question' => 'Which sentence is in the passive voice?',
                'options' => json_encode([
                    'The company launched a new product.',
                    'A new product was launched by the company.',
                    'They are launching a new product.',
                    'The new product looks amazing.'
                ]),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 2
            ],
            [
                'level' => 'upper_intermediate',
                'question' => 'Choose the correct relative pronoun: The person ___ invented this was a genius.',
                'options' => json_encode(['which', 'whom', 'whose', 'who']),
                'correct_index' => 3,
                'type' => 'multiple_choice',
                'order' => 3
            ],
            [
                'level' => 'upper_intermediate',
                'question' => 'Identify the idiom: "He kicked the bucket."',
                'options' => json_encode(['He was angry', 'He died', 'He cleaned the house', 'He played football']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 4
            ],
            [
                'level' => 'upper_intermediate',
                'question' => 'Choose the correct tense: By next year, I ___ here for five years.',
                'options' => json_encode([
                    'will work',
                    'will have worked',
                    'will be working',
                    'have worked'
                ]),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 5
            ],

            // Advanced Level (C1)
            [
                'level' => 'advanced',
                'question' => 'Choose the correct inversion: ___ should you behave like that.',
                'options' => json_encode(['Under no circumstances', 'Never', 'Rarely', 'Only then']),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 1
            ],
            [
                'level' => 'advanced',
                'question' => 'Which sentence uses the subjunctive mood?',
                'options' => json_encode([
                    'I suggest that he go home.',
                    'I suggest that he goes home.',
                    'I suggested him to go home.',
                    'I suggest going home.'
                ]),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 2
            ],
            [
                'level' => 'advanced',
                'question' => 'Choose the correct collocation: They reached a ___ agreement.',
                'options' => json_encode(['tough', 'hard', 'strong', 'heavy']),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 3
            ],
            [
                'level' => 'advanced',
                'question' => 'Identify the meaning: "The project is on the back burner."',
                'options' => json_encode([
                    'The project is very important',
                    'The project is delayed or postponed',
                    'The project is almost finished',
                    'The project is very expensive'
                ]),
                'correct_index' => 1,
                'type' => 'multiple_choice',
                'order' => 4
            ],
            [
                'level' => 'advanced',
                'question' => 'Choose the correct participle: ___, the team celebrated their victory.',
                'options' => json_encode([
                    'Having won the match',
                    'Winning the match',
                    'Won the match',
                    'To win the match'
                ]),
                'correct_index' => 0,
                'type' => 'multiple_choice',
                'order' => 5
            ]
        ];

        foreach ($questions as $question) {
            PlacementQuestion::create($question);
        }

        $this->command->info('Placement test questions seeded successfully!');
    }
}
