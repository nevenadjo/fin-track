<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $userRole = Role::firstOrCreate(['name' => 'user']);

        for ($i = 1; $i <= 15; $i++) {
            $date = now()->subDays(rand(0,6))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            User::firstOrCreate([
                'email' => "user{$i}@fintrack.com",
            ], [
                'name' => "User {$i}",
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
                'currency_id' => rand(1,5),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
