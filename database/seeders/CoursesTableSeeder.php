<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CoursesTableSeeder extends Seeder
{
    public function run(): void
    {
        Course::insert([
            ['title' => 'Mathematics 101', 'description' => 'Basic mathematics course', 'teacher_id' => 1],
            ['title' => 'Physics 101', 'description' => 'Introduction to physics', 'teacher_id' => 2],
            ['title' => 'Chemistry 101', 'description' => 'Basic chemistry concepts', 'teacher_id' => 1],
        ]);
    }
}
