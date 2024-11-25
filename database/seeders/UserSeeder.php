<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "Colaborador2",
            "cod_user" => "123456789",
            "password" => Hash::make("123456789"),
            "role" => "collaborator",
            "workplace_id" => 20,
            "schedule_id" => 3,
            "photo" => "https://via.placeholder.com/150",
        ]);

        /*   User::create([
            "username" => "Facilitador",
            "cod_user" => "1234567891",
            "password" => Hash::make("1234567891"),
            "role" => "facilitator",
            "workplace_id" => 1,
        ]);

        User::create([
            "username" => "RRHH",
            "cod_user" => "1234567892",
            "password" => Hash::make("1234567892"),
            "role" => "rrhh",
            "workplace_id" => 1,
        ]); */
    }
}
