<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CurrencySeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TransactionSeeder::class,
            LogSeeder::class,
        ]);
    }
}
