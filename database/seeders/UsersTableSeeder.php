<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        
        User::insert([
            ['name' => 'Ella Grey', 'email' => 'ella.grey@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City F', 'approved' => true],
            ['name' => 'Oliver Twist', 'email' => 'oliver.twist@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City G', 'approved' => true],
            ['name' => 'Isaac Newton', 'email' => 'isaac.newton@example.com', 'password' => Hash::make('password'), 'role' => 'student', 'city' => 'City H', 'approved' => false],
            ['name' => 'Ada Lovelace', 'email' => 'ada.lovelace@example.com', 'password' => Hash::make('password'), 'role' => 'student', 'city' => 'City I', 'approved' => false],
            ['name' => 'Marie Curie', 'email' => 'marie.curie@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City J', 'approved' => true],
            ['name' => 'Thomas Edison', 'email' => 'thomas.edison@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City K', 'approved' => true],
            ['name' => 'Nikola Tesla', 'email' => 'nikola.tesla@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City L', 'approved' => true],
            ['name' => 'Leonardo Da Vinci', 'email' => 'leonardo.davinci@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City M', 'approved' => true],
            ['name' => 'Grace Hopper', 'email' => 'grace.hopper@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City N', 'approved' => true],
            ['name' => 'Albert Einstein', 'email' => 'albert.einstein@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City O', 'approved' => true],
            ['name' => 'Galileo Galilei', 'email' => 'galileo.galilei@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City P', 'approved' => true],
            ['name' => 'Neil Armstrong', 'email' => 'neil.armstrong@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City Q', 'approved' => true],
            ['name' => 'Stephen Hawking', 'email' => 'stephen.hawking@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City R', 'approved' => true],
            ['name' => 'Carl Sagan', 'email' => 'carl.sagan@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City S', 'approved' => true],
            ['name' => 'Edwin Hubble', 'email' => 'edwin.hubble@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City T', 'approved' => true],
            ['name' => 'Rosalind Franklin', 'email' => 'rosalind.franklin@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City U', 'approved' => true],
            ['name' => 'Lise Meitner', 'email' => 'lise.meitner@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City V', 'approved' => true],
            ['name' => 'James Watson', 'email' => 'james.watson@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City W', 'approved' => true],
            ['name' => 'Francis Crick', 'email' => 'francis.crick@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City X', 'approved' => true],
            ['name' => 'Gregor Mendel', 'email' => 'gregor.mendel@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City Y', 'approved' => true],
            ['name' => 'Charles Darwin', 'email' => 'charles.darwin@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City Z', 'approved' => true],
            ['name' => 'Jane Goodall', 'email' => 'jane.goodall@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AA', 'approved' => true],
            ['name' => 'Michael Faraday', 'email' => 'michael.faraday@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AB', 'approved' => true],
            ['name' => 'Dmitri Mendeleev', 'email' => 'dmitri.mendeleev@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AC', 'approved' => true],
            ['name' => 'Max Planck', 'email' => 'max.planck@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AD', 'approved' => true],
            ['name' => 'Katherine Johnson', 'email' => 'katherine.johnson@example.com', 'password' => Hash::make('password'), 'role' => 'student', 'city' => 'City AE', 'approved' => false],
            ['name' => 'Ernest Rutherford', 'email' => 'ernest.rutherford@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AF', 'approved' => true],
            ['name' => 'Niels Bohr', 'email' => 'niels.bohr@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AG', 'approved' => true],
            ['name' => 'Louis Pasteur', 'email' => 'louis.pasteur@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AH', 'approved' => true],
            ['name' => 'Alexander Fleming', 'email' => 'alexander.fleming@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AI', 'approved' => true],
            ['name' => 'John Dalton', 'email' => 'john.dalton@example.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'city' => 'City AJ', 'approved' => true],
        ]);
        
    }
}


