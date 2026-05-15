<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user1@gmail.com'],
            [
                'name' => 'User 1',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@gmail.com'],
            [
                'name' => 'User 2',
                'password' => Hash::make('password'),
            ]
        );
    }
}
