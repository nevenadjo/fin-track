<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::whereHas('role', fn($q)=>$q->where('name','user'))->get();
        $categories = Category::all();
        $incomeCategories = Category::where('type', 'income');

        foreach ($users as $user) {
            if ($user->email == 'user1@fintrack.com') {
                for ($i = 0; $i < 200; $i++) {
                    $randomDate = now()->subDays(rand(0, 365))->subHours(rand(0,23))->subMinutes(rand(0,59));
                    Transaction::create([
                        'user_id' => $user->id,
                        'category_id' => $categories->random()->id,
                        'amount' => rand(5, 500),
                        'date' => $randomDate,
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate,
                    ]);
                }
                for ($i = 0; $i < 10; $i++) {
                    $randomDate = now()->subDays(rand(0, 30))->subHours(rand(0,23))->subMinutes(rand(0,59));
                    Transaction::create([
                        'user_id' => $user->id,
                        'category_id' => $incomeCategories->random()->id,
                        'amount' => rand(5, 500),
                        'date' => $randomDate,
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate,
                    ]);
                }
                for ($i = 0; $i < 10; $i++) {
                    $randomDate = now()->subDays(rand(30, 60))->subHours(rand(0,23))->subMinutes(rand(0,59));
                    Transaction::create([
                        'user_id' => $user->id,
                        'category_id' => $incomeCategories->random()->id,
                        'amount' => rand(5, 500),
                        'date' => $randomDate,
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate,
                    ]);
                }
            } else {
                for ($i = 0; $i < rand(2,10); $i++) {
                    $randomDate = now()->subDays(rand(0, 7))->subHours(rand(0,23))->subMinutes(rand(0,59));
                    Transaction::create([
                        'user_id' => $user->id,
                        'category_id' => $categories->random()->id,
                        'amount' => rand(5, 300),
                        'date' => $randomDate,
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate,
                    ]);
                }
            }
        }
    }
}
