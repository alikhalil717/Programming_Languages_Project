<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('updaterentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->date('new_start_date');
            $table->date('new_end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updaterentals');
    }
};
