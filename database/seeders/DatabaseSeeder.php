<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'name' => "Alexander Texas",
            'email' => "alexander@gmail.com",
            'role' => "drop_manager",
            'password' => Hash::make("alexanderalexander"),
        ]);

        User::create([
            'name' => "Noran Appiggo",
            'email' => "appiggo@gmail.com",
            'role' => "company_creator",
            'password' => Hash::make("appiggoappiggo"),
        ]);

        User::create([
            'name' => "Stannis Mahon",
            'email' => "mahon@gmail.com",
            'role' => "website_creator",
            'password' => Hash::make("mahonmahon"),
        ]);

        User::create([
            'name' => "Norax Lom",
            'email' => "norax@gmail.com",
            'role' => "support",
            'password' => Hash::make("noraxnorax"),
        ]);

        User::create([
            'name' => "madmans",
            'email' => "madmans@gmail.com",
            'role' => "admin",
            'password' => Hash::make("madmansmadmans"),
        ]);
    }
}
