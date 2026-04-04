<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Salary', 'type' => 'income', 'user_id' => null],
            ['name' => 'Freelance', 'type' => 'income', 'user_id' => null],
            ['name' => 'Gift', 'type' => 'income', 'user_id' => null],
            ['name' => 'Investment', 'type' => 'income', 'user_id' => null],
            ['name' => 'Bonus', 'type' => 'income', 'user_id' => null],
            ['name' => 'Food', 'type' => 'expense', 'user_id' => null],

            ['name' => 'Transport', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Shopping', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Rent', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Entertainment', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Health', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Education', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Utilities', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Subscriptions', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Travel', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Personal Care', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Loan', 'type' => 'expense', 'user_id' => null],
        ];

        DB::table('categories')->insert($categories);
    }
}
