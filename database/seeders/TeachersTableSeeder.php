<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Category;

class TeachersTableSeeder extends Seeder
{
    public function run()
    {
        // استرداد المستخدمين الذين لديهم دور "teacher" والفئات
        $teachers = User::where('role', 'teacher')->get();
        $categories = Category::all();

        foreach ($teachers as $index => $teacher) {
            // توزيع الأساتذة على الفئات بشكل دوري
            $category = $categories[$index % $categories->count()];

            Teacher::create([
                'user_id' => $teacher->id,
                'cv_path' => 'path/to/cv' . $teacher->id . '.pdf',
                'category_id' => $category->id,
            ]);
        }
    }
}
