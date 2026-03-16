<?php

namespace Database\Seeders;

use App\Models\Blog;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User factory to create 500 users
        User::factory(500)->create();

        // Blog factory to create 1000+ blogs
        Blog::factory(1100)->create();        
    }
}
