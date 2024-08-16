<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'teacher_id',
        'type',
        'path',
        'url',
        'description',
        'Category_id',
    ];

    public function Teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
