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
            "username" => "Colaborador",
            "cod_user" => "1234567890",
            "password" => Hash::make("1234567890"),
            "role" => "collaborator",
            "workplace_id" => 1,
        ]);

        User::create([
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
        ]);
    }
}
