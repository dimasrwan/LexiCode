<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Module;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LexiCodeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada User (Ganti email sesuai keinginan)
        $user = User::first() ?? User::factory()->create(['email' => 'admin@lexicode.test']);

        // 2. Buat Project
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'LexiCode Core API',
            'slug' => Str::slug('LexiCode Core API'),
            'description' => 'Dokumentasi internal untuk pengembangan engine LexiCode.',
            'tech_stack' => 'Laravel 12, Tailwind CSS',
        ]);

        // 3. Buat Module
        $module = Module::create([
            'project_id' => $project->id,
            'title' => 'Authentication Protocol',
            'order' => 1,
        ]);

        // 4. Buat Snippet (Inilah kekuatan LexiCode!)
        Snippet::create([
            'module_id' => $module->id,
            'title' => 'Login Logic',
            'code_block' => "public function login(Request \$request) { \n   return Auth::attempt(\$request->only('email', 'password')); \n}",
            'human_explanation' => 'Fungsi ini digunakan untuk memverifikasi apakah email dan password yang dimasukkan user sudah sesuai dengan data di database.',
            'language' => 'php',
        ]);
    }
}