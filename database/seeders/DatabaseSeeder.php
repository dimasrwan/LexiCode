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
        // 1. Membuat User Admin Utama
        // Kita gunakan updateOrCreate agar tidak duplikat jika seeder dijalankan ulang
        User::updateOrCreate(
            ['email' => 'admin@lexicode.test'],
            [
                'name' => 'Admin LexiCode',
                'password' => Hash::make('password'), // Menggunakan Hash untuk keamanan
            ]
        );

        // 2. Memanggil LexiCodeSeeder
        // Ini akan mengisi tabel projects, modules, dan snippets
        $this->call([
            LexiCodeSeeder::class,
        ]);
    }
}