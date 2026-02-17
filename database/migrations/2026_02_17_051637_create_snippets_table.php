<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('snippets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('module_id')->constrained()->onDelete('cascade'); // Masuk ke modul mana
        $table->string('title'); // Nama fungsi/fitur
        $table->text('code_block'); // Kode teknis mentah
        $table->text('human_explanation'); // Penjelasan versi simpel
        $table->string('language')->default('php'); // Bahasa pemrograman
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snippets');
    }
};
