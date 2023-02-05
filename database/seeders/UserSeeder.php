<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Desenvolvedor',
            'birth_date' => date('1990-03-01'),
            'main_contact' => 88992832962,
            'secondary_contact' => null,
            'user_image' => 'DEVELOP-IMAGE-CLINICA.png',
            'email' => 'develop@clinica.com',
            'password' => Hash::make('@dev&L0P'),
            'status'=> true
        ]);

        DB::table('users')->insert([
            'name' => 'Administrador',
            'birth_date' => date('1990-03-01'),
            'main_contact' => 8892122365,
            'secondary_contact' => null,
            'user_image' => 'ADMIN-IMAGE-CLINICA.png',
            'email' => 'admin@clinica.com',
            'password' => Hash::make('A#d&m@iN'),
            'status'=> true
        ]);
    }
}
