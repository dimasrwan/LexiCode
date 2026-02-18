<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::factory()->create([
        'id' => 1,
        'name' => 'Admin LexiCode',
        'email' => 'admin@lexicode.test',
    ]);

        // 2. Memanggil LexiCodeSeeder
        // Ini akan mengisi tabel projects, modules, dan snippets
        $this->call([
            LexiCodeSeeder::class,
        ]);
    }
}