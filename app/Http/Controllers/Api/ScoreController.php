<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Unit;
use App\Models\Question;
use App\Models\Answer;

class ScoreController extends Controller
{
    public function getQuestions($unit_id)
    {
        $questions = Question::where('unit_id', $unit_id)->with('answers')->get();
        return response()->json($questions);
    }
    public function submitAnswers(Request $request, $unit_id)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id' => 'required|exists:answers,id',
        ]);

        $correctAnswersCount = 0;
        $totalQuestions = count($request->answers);

        foreach ($request->answers as $answerData) {
            $question = Question::find($answerData['question_id']);
            $selectedAnswer = Answer::find($answerData['answer_id']);

            // تحقق مما إذا كان الجواب المحدد ينتمي للسؤال الصحيح
            if ($selectedAnswer && $selectedAnswer->question_id === $question->id) {
                if ($selectedAnswer->is_correct) {
                    $correctAnswersCount++;
                }
            } else {
                return response()->json([
                    'message' => 'Invalid answer selection. The selected answer does not match the question.'
                ], 422);
            }
        }

        // حساب العلامة النهائية كنسبة مئوية
        $finalScore = ($correctAnswersCount / $totalQuestions) * 100;

        // تخزين العلامة النهائية في قاعدة البيانات
        $score = Score::updateOrCreate(
            ['unit_id' => $unit_id, 'student_id' => auth()->id()],
            ['score' => round($finalScore, 2)] // تخزين العلامة بعد التقريب إلى منزلتين عشريتين
        );

        return response()->json([
            'message' => 'Test submitted successfully',
            'final_score' => round($finalScore, 2) . '%', // عرض العلامة مع التقريب إلى منزلتين عشريتين
        ]);
    }

    public function getFinalScore($unit_id)
    {
        $score = Score::where('unit_id', $unit_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$score) {
            return response()->json(['message' => 'Score not found for this unit'], 404);
        }

        return response()->json([
            'unit_id' => $unit_id,
            'student_id' => auth()->id(),
            'final_score' => round($score->score, 2) . '%', // تقريب العلامة إلى منزلتين عشريتين
        ]);
    }

    public function getUnitScore($unit_id)
    {
        $score = Score::where('unit_id', $unit_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$score) {
            return response()->json(['message' => 'Score not found for this unit'], 404);
        }

        return response()->json([
            'unit_id' => $unit_id,
            'student_id' => auth()->id(),
            'final_score' => round($score->score, 2) . '%', // تقريب العلامة إلى منزلتين عشريتين
        ]);
    }

    public function getCourseScore($course_id)
    {
        // جلب جميع الوحدات الخاصة بالكورس
        $units = Unit::where('course_id', $course_id)->pluck('id');
        
        // جلب العلامات الخاصة بكل وحدة في الكورس
        $scores = Score::whereIn('unit_id', $units)
            ->where('student_id', auth()->id())
            ->get();

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'Scores not found for this course'], 404);
        }

        // حساب المجموع الكلي للعلامات والنسبة المئوية النهائية
        $totalScore = $scores->sum('score');
        $totalUnits = $scores->count();
        $finalCourseScore = round($totalScore / $totalUnits, 2);

        return response()->json([
            'course_id' => $course_id,
            'student_id' => auth()->id(),
            'final_course_score' => $finalCourseScore . '%', // تقريب العلامة إلى منزلتين عشريتين
        ]);
    }
}