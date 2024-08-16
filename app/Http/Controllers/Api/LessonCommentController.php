<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Lesson; 
use Illuminate\Support\Facades\Auth;

class LessonCommentController extends Controller
{
    public function store(Request $request, $lesson_id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $lesson = Lesson::findOrFail($lesson_id);

        $comment = Comment::create([
            'lesson_id' => $lesson->id,  
            'user_id' => auth()->id(),   
            'comment' => $request->input('comment'),
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ]);
    }

    public function index($lesson_id)
    {
        $comments = Comment::where('lesson_id', $lesson_id)->with('user')->get();
        return response()->json($comments);
    }
}
