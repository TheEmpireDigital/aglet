<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'jointheteam',
            'name' => 'jointheteam',
            'email' => 'jointheteam@aglet.co.za',
            'password' => Hash::make('@TeamAglet'),
            'email_verified_at' => now(),
        ]);
    }
}
