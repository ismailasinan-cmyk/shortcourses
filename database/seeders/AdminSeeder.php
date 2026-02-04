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
        \App\Models\User::create([
            'firstname' => 'Ismaila',
            'surname' => 'Sinan',
            'email' => 'isinan@noun.edu.ng',
            'password' => \Illuminate\Support\Facades\Hash::make('Sinan3367#'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        \App\Models\User::create([
            'firstname' => 'Admin',
            'surname' => 'Bello',
            'email' => 'abbello@noun.edu.ng',
            'password' => \Illuminate\Support\Facades\Hash::make('Bello3367#'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
    }
}
