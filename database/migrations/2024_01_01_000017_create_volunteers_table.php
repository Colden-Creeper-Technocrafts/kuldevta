<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sangh_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('mobile', 15);
            $table->string('village_city')->nullable();
            $table->enum('role', ['coordinator', 'registration_desk', 'stoppage_service', 'medical', 'security', 'general'])->default('general');
            $table->foreignId('assigned_stoppage_id')->nullable()->constrained('stoppages')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
