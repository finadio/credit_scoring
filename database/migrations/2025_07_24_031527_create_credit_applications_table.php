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
        Schema::create('credit_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('application_date');
            $table->decimal('loan_amount', 15, 2);
            $table->integer('tenor_months');
            $table->enum('application_status', ['pending', 'on_review', 'approved', 'rejected'])->default('pending');
            $table->integer('final_score')->nullable(); // Total score
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict'); // Direksi user ID
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict'); // Teller user ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_applications');
    }
};