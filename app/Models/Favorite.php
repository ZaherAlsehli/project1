<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'course_enrolled_id',
        'video_id'
    ];


    public function Lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function course_enrolled()
    {
        return $this->hasMany(course_enrolled::class);
    }
}
