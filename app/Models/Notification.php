<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'Message',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo( user::class);
    }
}
