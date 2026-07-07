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
        Schema::create('sangh_hawan_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sangh_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['main', 'support_1', 'support_2', 'support_3', 'support_4']);
            $table->timestamps();
            $table->unique(['sangh_id', 'role']); // one person per slot
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sangh_hawan_assignments');
    }
};
