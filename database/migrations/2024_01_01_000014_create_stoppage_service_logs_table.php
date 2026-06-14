<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stoppage_service_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stoppage_id')->constrained()->cascadeOnDelete();
            $table->enum('service_type', ['water', 'food', 'tea', 'medical', 'rest']);
            $table->unsignedInteger('count')->default(1);
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('logged_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stoppage_service_logs');
    }
};
