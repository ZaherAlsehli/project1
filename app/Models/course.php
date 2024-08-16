<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class course extends Model
{
    use HasFactory , Notifiable;

    protected $fillable = ['title', 'description', 'teacher_id'];

    public function Category()
    
    {
        return $this->belongsTo(Category::class);
    }
    public function Teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function units() {
        return $this->hasMany(Unit::class);
    }
  
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_enrolleds', 'course_id', 'student_id');
    }
    public function enrolledStudents()
    {
        return $this->belongsToMany(Student::class, 'new_course_enrollments', 'course_id', 'student_id');
    }
    
    
}
