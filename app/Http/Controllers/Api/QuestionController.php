<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function store(Request $request, $unit_id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'correct' => 'required|integer|min:0',
        ]);

        $unit = Unit::findOrFail($unit_id);

        if (auth()->user()->teacher->id !== $unit->course->teacher_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $question = new Question([
            'unit_id' => $unit_id,
            'question' => $request->question,
        ]);
        $question->save();

        foreach ($request->options as $index => $option) {
            $answer = new Answer([
                'question_id' => $question->id,
                'answer' => $option,
                'is_correct' => $index == $request->correct,
            ]);
            $answer->save();
        }

        return response()->json(['message' => 'Question and answers created successfully']);
    }

    public function show($unit_id, $question_id)
    {
        $unit = Unit::findOrFail($unit_id);
        $question = Question::with('answers')->where('unit_id', $unit_id)->findOrFail($question_id);
        return response()->json($question);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'sometimes|string|max:255',
            'options' => 'sometimes|array|min:2',
            'options.*' => 'sometimes|string|max:255',
            'correct' => 'sometimes|integer|min:0',
        ]);

        $question = Question::findOrFail($id);

        if (auth()->user()->teacher->id !== $question->unit->course->teacher_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $question->update($request->only('question'));

        if ($request->has('options')) {
            $question->answers()->delete();

            foreach ($request->options as $index => $option) {
                $answer = new Answer([
                    'question_id' => $question->id,
                    'answer' => $option,
                    'is_correct' => $index == $request->correct,
                ]);
                $answer->save();
            }
        }

        return response()->json(['message' => 'Question updated successfully']);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        if (auth()->user()->teacher->id !== $question->unit->course->teacher_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $question->delete();

        return response()->json(['message' => 'Question deleted successfully']);
    }

    public function getUnitQuestions($unit_id)
    {
        $unit = Unit::findOrFail($unit_id);
        $questions = Question::where('unit_id', $unit_id)->with('answers')->get();

        return response()->json($questions);
    }
}