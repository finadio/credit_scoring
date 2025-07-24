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
        Schema::create('scoring_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('parameter_name')->unique();
            $table->enum('category', ['UMKM/Pengusaha', 'Pegawai']);
            $table->text('description')->nullable();
            // Kolom untuk menyimpan bobot/nilai dasar/rentang
            // Ini bisa sangat bervariasi tergantung bagaimana Anda ingin menghitung skor.
            // Untuk contoh awal, kita akan asumsikan ada 'base_score' dan 'rules' yang lebih kompleks akan diimplementasikan di logic aplikasi.
            $table->json('rules')->nullable(); // Menyimpan aturan sebagai JSON (e.g., {"min": 5, "score": 20}, {"max": 10, "score": 10}, {"value": "Besar", "score": 30})
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_parameters');
    }
};