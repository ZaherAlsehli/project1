<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;
    use HasRoles, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'role',
        'approved',
    ];

    
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function Notification()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }
    public function isTeacher()
{
    return $this->role === 'teacher'; 
}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved' => 'boolean',

    ];

      // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function favorites()
{
    return $this->belongsToMany(Lesson::class, 'favorites')
                ->withPivot('favorite')
                ->withTimestamps();
}
}
