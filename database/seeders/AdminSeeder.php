<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'isinan@noun.edu.ng'],
            [
                'firstname' => 'Ismaila',
                'surname' => 'Sinan',
                'password' => \Illuminate\Support\Facades\Hash::make('Sinan3367#'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'abbello@noun.edu.ng'],
            [
                'firstname' => 'Admin',
                'surname' => 'Bello',
                'password' => \Illuminate\Support\Facades\Hash::make('Bello3367#'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );
    }
}
