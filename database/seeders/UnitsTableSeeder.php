<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitsTableSeeder extends Seeder
{
    public function run(): void
    {
        Unit::insert([
            ['unit_name' => 'Algebra', 'course_id' => 1],
            ['unit_name' => 'Calculus', 'course_id' => 1],
            ['unit_name' => 'Mechanics', 'course_id' => 2],
            ['unit_name' => 'Thermodynamics', 'course_id' => 2],
            ['unit_name' => 'Organic Chemistry', 'course_id' => 3],
            ['unit_name' => 'Inorganic Chemistry', 'course_id' => 3],
        ]);
    }
}
