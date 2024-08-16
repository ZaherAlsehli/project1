<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'question'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
