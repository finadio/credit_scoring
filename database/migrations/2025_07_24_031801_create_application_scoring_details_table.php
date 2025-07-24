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
        Schema::create('application_scoring_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_application_id')->constrained('credit_applications')->onDelete('cascade');
            $table->foreignId('scoring_parameter_id')->constrained('scoring_parameters')->onDelete('cascade');
            $table->string('input_value')->nullable(); // Nilai yang diinput teller (misal: "Besar", "3 Tahun")
            $table->integer('calculated_score'); // Skor yang dihitung untuk parameter ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_scoring_details');
    }
};