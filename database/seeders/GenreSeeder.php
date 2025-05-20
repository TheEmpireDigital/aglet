<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TmdbService;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        app(TmdbService::class)->fetchAndStoreGenres();
    }
} 