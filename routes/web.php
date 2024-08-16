<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\FrontendController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\FavoriteController;
use App\Http\Controllers\Web\CourseQuizController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::group(['middleware' => ['auth']], function () {

Route::get('/index', [FrontendController::class, 'index'])->name('course.single');

    Route::post('/course/{course_id}/submit-quiz', [CourseQuizController::class, 'submitAnswers'])->name('unit.submit');
    Route::post('/lessons/{lesson}/comments', [FrontendController::class, 'store'])->name('lesson.comment.store');

    Route::post('/course/{id}/enroll', [FrontendController::class, 'enroll'])->name('course.enroll');
    Route::get('/courses/{id}/units', [FrontendController::class, 'showUnits'])->name('course.units');
    Route::get('/lessons/{lesson}/watch', [FrontendController::class, 'watch'])->name('lesson.watch');

    Route::post('/favorites/{lesson}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
// });

Auth::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
