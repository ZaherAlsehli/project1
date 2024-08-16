<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AUTH_Users;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\LessonCommentController;


Route::post('register', [AUTH_Users::class, 'register']);
Route::post('login', [AUTH_Users::class, 'login']);
Route::post('adminLogin', [AUTH_Users::class, 'adminLogin']);
Route::post('logout', [AUTH_Users::class, 'logout']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('profile', [AUTH_Users::class, 'profile']);
    Route::put('approveTeacher/{id}', [AUTH_Users::class, 'approveTeacher'])->middleware('checkAdmin');
    Route::put('rejectTeacher/{id}', [AUTH_Users::class, 'rejectTeacher'])->middleware('checkAdmin');
    Route::post('updateProfile/{id}', [AUTH_Users::class, 'updateProfile']);
    Route::post('updateAdminProfile/{id}', [AUTH_Users::class, 'updateAdminProfile']);
    Route::get('/print-teachers', [AUTH_Users::class, 'printTeachers']);
    Route::get('/pending-teachers', [AUTH_Users::class, 'listPendingTeachers'])->middleware('auth:api', 'checkAdmin');


    
    Route::delete('/admin/users/{id}', [AdminController::class, 'softDeleteUser'])->middleware('auth:api');
    Route::patch('/admin/users/{id}/restore', [AdminController::class, 'restoreUser'])->middleware('auth:api');
    Route::delete('/admin/users/{id}/force', [AdminController::class, 'forceDeleteUser'])->middleware('auth:api');

});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
//Route::get('/categories/{id}', 'CategoryController@show');

// Route::middleware('auth:api')->group(function () {
//     Route::post('/courses', [CourseController::class, 'store']);
//     Route::put('/courses/{course}', [CourseController::class, 'update']);
//     Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
// });

// Route::post('/courses/{course_id}/videos', [VideoController::class,'store']);
// Route::put('/videos/{video}', [VideoController::class,'update']);
// Route::delete('/videos/{video}',  [VideoController::class,'destroy']);



// Courses
    Route::get('/courses/{teacherId}', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    // Units
    Route::get('/units/{courseId}', [UnitController::class, 'index']);
    Route::post('/units', [UnitController::class, 'store']);
    Route::put('/units/{id}', [UnitController::class, 'update']);
    Route::delete('/units/{id}', [UnitController::class, 'destroy']);
    Route::get('show_units/{unit_id}', [UnitController::class, 'show']);

    // Lesson 
    Route::middleware(['auth:api'])->group(function () {
    Route::get('/units/{unit_id}/lessons', [LessonController::class, 'index']);
    Route::post('units/{unit_id}/lessons', [LessonController::class, 'store']); 
    Route::get('lessons/{lesson_id}', [LessonController::class, 'show']); 
    Route::put('lessons/{lesson_id}', [LessonController::class, 'update']); 
    Route::delete('lessons/{lesson_id}', [LessonController::class, 'destroy']); 
});
    // Subscribe 
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/enroll', [EnrollmentController::class, 'enroll']);
        Route::post('/unenroll', [EnrollmentController::class, 'unenroll']);
    });
    
    Route::post('/files', [PdfController::class, 'uploadFile'])->name('files.upload');

    // Questions 
    Route::middleware(['auth:api'])->group(function () {
        Route::post('units/{unitId}/questions', [QuestionController::class, 'store']);
        Route::put('questions/{question_id}', [QuestionController::class, 'update']);
        Route::delete('questions/{question_id}', [QuestionController::class, 'destroy']);


    });

        Route::get('units/{unitId}/questions/{question_id}', [QuestionController::class, 'show']);
        Route::get('units/{unit}/questions', [QuestionController::class, 'getUnitQuestions']);
        Route::middleware('auth:api')->group(function () {

        Route::post('units/{unit_id}/submit-answers', [ScoreController::class, 'submitAnswers']);
    });
        Route::get('units/{unit_id}/score', [ScoreController::class, 'getFinalScore']);
        Route::get('units/{unit_id}/score', [ScoreController::class, 'getUnitScore']);
        Route::get('courses/{course_id}/score', [ScoreController::class, 'getCourseScore']);
            

        //comment

            Route::post('lessons/{lesson}/comments', [LessonCommentController::class, 'store']);
            Route::get('lessons/{lesson}/comments', [LessonCommentController::class, 'index']);

        Route::get('/all-teachers', [AdminController::class, 'allTeachers']);
        Route::get('/all-students', [AdminController::class, 'allStudents']);


        Route::middleware(['auth:api'])->group(function () {
            Route::get('notifications', [EnrollmentController::class, 'getNotifications']); // You can add this route in EnrollmentController
        });

        Route::post('/courses/{course}/sendToStudents', [CourseController::class, 'sendMessageToStudents'])->middleware('auth:api');

        Route::get('admin/courses', [CourseController::class, 'getAllCourses']);
        Route::get('/admin/courses/scores', [CourseController::class, 'getAllScoresForCourses'])->name('admin.courses.scores');
