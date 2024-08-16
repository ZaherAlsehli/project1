<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'student')->get();

        foreach ($users as $user) {
            Student::create([
                'user_id' => $user->id
            ]);
        }
    }
}
