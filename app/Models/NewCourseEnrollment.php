<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCourseEnrollment extends Model
{
    use HasFactory;

    protected $table = 'new_course_enrollments'; 

    protected $fillable = [
        'student_id',
        'course_id',
    ];
}
