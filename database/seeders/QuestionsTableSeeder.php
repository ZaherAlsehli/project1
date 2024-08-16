<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            [
                'unit_id' => 1,
                'question' => 'What is the capital of France?',
                'answers' => [
                    ['answer' => 'Paris', 'is_correct' => true],
                    ['answer' => 'London', 'is_correct' => false],
                    ['answer' => 'Berlin', 'is_correct' => false],
                    ['answer' => 'Madrid', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 1,
                'question' => 'What is the largest planet in our solar system?',
                'answers' => [
                    ['answer' => 'Earth', 'is_correct' => false],
                    ['answer' => 'Jupiter', 'is_correct' => true],
                    ['answer' => 'Mars', 'is_correct' => false],
                    ['answer' => 'Venus', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 2,
                'question' => 'What is the chemical symbol for water?',
                'answers' => [
                    ['answer' => 'H2O', 'is_correct' => true],
                    ['answer' => 'O2', 'is_correct' => false],
                    ['answer' => 'CO2', 'is_correct' => false],
                    ['answer' => 'HO', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 2,
                'question' => 'What is the speed of light?',
                'answers' => [
                    ['answer' => '300,000 km/s', 'is_correct' => true],
                    ['answer' => '150,000 km/s', 'is_correct' => false],
                    ['answer' => '100,000 km/s', 'is_correct' => false],
                    ['answer' => '1,000 km/s', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 3,
                'question' => 'Who wrote "Romeo and Juliet"?',
                'answers' => [
                    ['answer' => 'William Shakespeare', 'is_correct' => true],
                    ['answer' => 'Charles Dickens', 'is_correct' => false],
                    ['answer' => 'Mark Twain', 'is_correct' => false],
                    ['answer' => 'Jane Austen', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 3,
                'question' => 'What is the square root of 64?',
                'answers' => [
                    ['answer' => '6', 'is_correct' => false],
                    ['answer' => '7', 'is_correct' => false],
                    ['answer' => '8', 'is_correct' => true],
                    ['answer' => '9', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 4,
                'question' => 'كيف حالك?',
                'answers' => [
                    ['answer' => 'بخير', 'is_correct' => false],
                    ['answer' => 'لست جيدا', 'is_correct' => false],
                    ['answer' => 'الحمدلله', 'is_correct' => true],
                    ['answer' => 'اتس اوك', 'is_correct' => false],
                ],
            ],
            [
                'unit_id' => 4,
                'question' => 'من انت?',
                'answers' => [
                    ['answer' => 'زاهر', 'is_correct' => true],
                    ['answer' => 'رامي', 'is_correct' => false],
                    ['answer' => 'وليد', 'is_correct' =>false ],
                    ['answer' => 'يمان', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $question = Question::create([
                'unit_id' => $questionData['unit_id'],
                'question' => $questionData['question'],
            ]);

            foreach ($questionData['answers'] as $answer) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer' => $answer['answer'],
                    'is_correct' => $answer['is_correct'],
                ]);
            }
        }
    }
}
