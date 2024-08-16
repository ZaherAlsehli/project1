<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Question;
use App\Models\Answer;

class CourseQuizController extends Controller
{
    public function submitAnswers(Request $request, $course_id)
    {
        $units = Unit::with('questions.answers')->where('course_id', $course_id)->get();

        foreach ($units as $unit) {
            foreach ($unit->questions as $question) {
                $selectedAnswerId = $request->input('question_' . $question->id);
                
                if ($selectedAnswerId) {
                    $answer = Answer::find($selectedAnswerId);
                    // هنا يمكنك إضافة منطق حفظ الإجابات أو أي عمليات أخرى تحتاجها
                }
            }
        }

        return redirect()->back()->with('message', 'Quiz submitted successfully!');
    }
}
