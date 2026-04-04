<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $logs = [
            'transaction_create' => 'Transaction created ',
            'transaction_update' => 'Transaction updated ',
            'transaction_delete' => 'Transaction deleted ',

            'user_login' => 'User logged in ',
            'user_register' => 'User registered ',
            'user_block' => 'User blocked ',

            'category_create' => 'Category created ',
            'category_update' => 'Category updated ',
            'category_delete' => 'Category deleted '
        ];

        foreach (range(0, 6) as $day) {
            foreach ($users as $user) {
                $action = Arr::random(array_keys($logs));

                $date = now()->subDays($day)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                Log::create([
                    'user_id'    => $user->id,
                    'action'     => $action,
                    'message'    => $logs[$action] . ' ' . $date->format('Y-m-d H:i:s'),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
