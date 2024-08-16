<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Teacher; 
use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\NewCourseEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class FrontendController extends Controller
{
   
    public function index()
    {
                $courses = Course::with('units.lessons')->get();
    
        foreach ($courses as $course) {
            $course->units_count = $course->units->count();
            $course->lessons_count = $course->units->reduce(function ($carry, $unit) {
                return $carry + $unit->lessons->count();
            }, 0);
        }
    
        $teachers = Teacher::with('user', 'category')
            ->take(3)
            ->get();
    
        return view('index', compact('courses', 'teachers')); 
    }

    public function enroll($courseId)
    {
    
        // Check if the user is already enrolled in the course
        $existingEnrollment = NewCourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
    
        if ($existingEnrollment) {
            return redirect()->back()->with('message', 'You are already enrolled in this course.');
        }
    
        // Add the new enrollment
        $enrollment = new NewCourseEnrollment();
        $enrollment->user_id = $user->id;
        $enrollment->course_id = $courseId;
        $enrollment->save();
    
        return redirect()->back()->with('message', 'You have successfully enrolled in the course.');
    }
    



    public function showUnits($id)
    {
        // جلب الدورة مع الوحدات والدروس المرتبطة بها
        $course = Course::with('units')->findOrFail($id);
        return view('units', compact('course'));
    }

    public function showLessons($unit_id)
    {
        $unit = Unit::with('lessons')->findOrFail($unit_id);
        return view('lessons', compact('unit'));
    }

    public function watch(Lesson $lesson)
    {
        $lesson->load('comments.user'); // لتحميل التعليقات مع أسماء المستخدمين
        return view('watch', compact('lesson'));
    }


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

        if (!auth()->check()) {
            return redirect()->back()->with('error', 'You must be logged in to add a comment.');
        }    }
}   
