<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\teacher;
class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'Category_name',
        'Descriprtion'
    ];
    public function course()
    {
        return $this->hasMany(course::class);
    }
    public function Library()
    {
        return $this->hasMany(Library::class);
    }
    public function PendingTeacher()
    {
        return $this->hasMany(PendingTeacher::class);
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'category_id');
    }
   
}
