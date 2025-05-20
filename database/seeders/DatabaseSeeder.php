<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserFavorite;
use App\Models\Movie;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        User::truncate();
        UserFavorite::truncate();
        Movie::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call([
            DefaultUserSeeder::class,
            GenreSeeder::class,
        ]);
    }
}
