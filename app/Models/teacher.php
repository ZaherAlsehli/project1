<?php

namespace App\Models;

use App\Models\User;
use App\Models\course;
use App\Models\category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class teacher extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $fillable = [
        'id',
        'user_id',
        'cv_path',
        'Category_id',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Library()
    {
        return $this->hasMany(Library::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
