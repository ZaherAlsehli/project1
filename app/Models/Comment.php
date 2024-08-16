<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['comment','lesson_id', 'user_id', ];




    public function Lesson()
    {
        return $this->hasMany(Lesson::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
