<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class student extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $fillable = [
        'id',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    // public function courses()
    // {
    //     return $this->belongsToMany(Course::class, 'course_enrolleds', 'student_id', 'course_id');
    // }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'new_course_enrollments', 'student_id', 'course_id');
    }
    
}
