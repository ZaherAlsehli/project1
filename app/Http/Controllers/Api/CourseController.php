<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class CourseController extends Controller
{
    public function index($teacherId)
    {
        $courses = Course::where('teacher_id', $teacherId)->get();
        return response()->json($courses);
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string'
    ]);

    $user = auth()->user();

    if (!$user->hasRole('teacher') && !$user->hasRole('admin')) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // إذا كان المستخدم Admin، فلا داعي للبحث عن teacher
    $teacherId = null;

    if ($user->hasRole('teacher')) {
        // إذا كان المستخدم معلم، استخدم معرّف المعلم
        $teacherId = $user->teacher->id;
    }

    if ($user->hasRole('admin')) {
        // إذا كان المستخدم Admin، لا تحتاج إلى تعيين teacher_id
        // يمكنك تعيينه لقيمة افتراضية أو تركه فارغاً
        $teacherId = 0;
    }

    // Create a new course
    $course = new Course([
        'title' => $request->title,
        'description' => $request->description,
        'teacher_id' => $teacherId // تعيين teacher_id فقط إذا كان للمستخدم دور المعلم
    ]);

    $course->save();

    return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
}

    
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->update($request->all());
        return response()->json(['message' => 'Course updated successfully', 'course' => $course]);
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function sendMessageToStudents(Request $request, $course_id)
    {
         //$teacher = Auth::user()->teacher;
        $course = Course::findOrFail($course_id);
        $message = $request->input('message');

        $students = $course->enrolledStudents;

        foreach ($students as $student) {
            $student->notify(new CourseMessageNotification($message, $course));
        }

        return response()->json(['message' => 'Message sent to all students and saved as notifications']);
    }

    public function getAllCourses()
{
    $courses = Course::with('teacher')->get(); 
    return response()->json($courses);
}



public function getAllScoresForCourses()
{
    // جلب جميع الكورسات مع الوحدات والعلامات المتعلقة بها
    $courses = Course::with(['units.scores.student'])->get();

    $coursesWithScores = $courses->map(function ($course) {
        return [
            'course_title' => $course->title,
            'students' => $course->units->flatMap(function ($unit) {
                return $unit->scores->map(function ($score) {
                    return [
                        'student_name' => $score->student->name,
                        'score' => $score->score,
                    ];
                });
            }),
        ];
    });

    return response()->json($coursesWithScores);
}

}    
