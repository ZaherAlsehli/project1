<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        Permission::create(['name' => 'approve teachers']);
        Permission::create(['name' => 'rejectTeacher']);
        Permission::create(['name' => 'edit courses']);
        Permission::create(['name' => 'delete courses']);
        Permission::create(['name' => 'publish courses']);
        Permission::create(['name' => 'create-student']);
        Permission::create(['name' => 'delete-student']);


        
        $adminRole->givePermissionTo(Permission::all());

        $teacherRole->givePermissionTo([
            'delete courses',
        
        ]);
        $studentRole->givePermissionTo([
            'delete-student',]);

$admin = User::create([
    'name' => 'Admin User',
    'email' => 'admin@gmail.com',
    'password' => bcrypt('admin12345678'),
    'role'=>'Admin',
    'approved'=>'1',

]);
$admin->assignRole('admin');

            $subjects = [
                ['Category_name' => 'Mathematics', 'Descriprtion' => 'Mathematics subject description.'],
                ['Category_name' => 'Physics', 'Descriprtion' => 'Physics subject description.'],
                ['Category_name' => 'Chemistry', 'Descriprtion' => 'Chemistry subject description.'],
                ['Category_name' => 'Arabic', 'Descriprtion' => 'Biology subject description.'],
                ['Category_name' => 'English,', 'Descriprtion' => 'Biology subject description.'],
                ['Category_name' => 'French', 'Descriprtion' => 'Biology subject description.'],
                ['Category_name' => 'Science', 'Descriprtion' => 'Biology subject description.'],
                ['Category_name' => 'Islamic Education', 'Descriprtion' => 'Biology subject description.'],
                ['Category_name' => 'National Education', 'Descriprtion' => 'Biology subject description.'],

            ];
    
            foreach ($subjects as $subject) {
                Category::create($subject);
            }


    }
}
