<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\NewCourseEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StudentEnrolledNotification;

class EnrollmentController extends Controller
{
    // تسجيل الطالب في الكورس
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Auth::user();
        $course = Course::findOrFail($request->input('course_id'));

        // التحقق من وجود اشتراك سابق للطالب في الكورس
        $existingEnrollment = NewCourseEnrollment::where('course_id', $course->id)
                                                 ->where('student_id', $student->id)
                                                 ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'You are already enrolled in this course.'], 409); // 409 Conflict
        }

        // تسجيل الاشتراك
        NewCourseEnrollment::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        // إرسال إشعار للمدرس
        $course->teacher->notify(new StudentEnrolledNotification($student, $course));

        return response()->json(['message' => 'You have successfully enrolled in the course.']);
    }

    // إلغاء اشتراك الطالب من الكورس
    public function unenroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Auth::user();
        $course = Course::findOrFail($request->input('course_id'));

        // التحقق من أن الطالب مشترك بالفعل في الكورس
        $enrollment = NewCourseEnrollment::where('course_id', $course->id)
                                         ->where('student_id', $student->id)
                                         ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 400);
        }

        // إلغاء الاشتراك
        $enrollment->delete();

        return response()->json(['message' => 'You have successfully unenrolled from the course.']);
    }



    public function getNotifications()
    {
        $teacher = Auth::user()->teacher;
        $notifications = $teacher->notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'], // استخراج الرسالة من الـ data
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ];
        });

        return response()->json($notifications);
    }



}
