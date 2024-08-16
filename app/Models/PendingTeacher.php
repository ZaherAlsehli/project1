<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'city', 'cv_path','Category_id',
    ];
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
