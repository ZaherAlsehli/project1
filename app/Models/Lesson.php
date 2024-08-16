<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
         'title', 'description', 'vimeo_uri', 'link' , 'unit_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites')
                ->withPivot('favorite')
                ->withTimestamps();
}
}
