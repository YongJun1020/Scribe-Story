<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
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
        User::factory()->createMany([
            [
                'name' => 'User 1',
                'email' => 'user1@example.com',
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@example.com',
            ],
        ]);

        $categories =[
            'Technology',
            'Health',
            'Travel',
            'Food',
            'Education',
            'Lifestyle',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        // Post::factory(100)->create();

        // To call other seeders, you can use the call method:
        // $this->call([
        //     PostSeeder::class,
        // ]);
    }
}
