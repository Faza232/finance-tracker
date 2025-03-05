<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = [
            ['name' => 'Gaji', 'type' => 'income', 'user_id'=>1],
            ['name' => 'Bonus', 'type' => 'income','user_id'=>1],
            ['name' => 'Makanan', 'type' => 'expense','user_id'=>1],
            ['name' => 'Transportasi', 'type' => 'expense','user_id'=>1],
            ['name' => 'Hiburan', 'type' => 'expense','user_id'=>1],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
