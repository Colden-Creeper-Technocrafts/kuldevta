<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sanghs', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();
            $table->string('title_en');
            $table->string('title_gu');
            $table->text('description_en')->nullable();
            $table->text('description_gu')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->default('05:00:00');
            $table->date('registration_open_from')->nullable();
            $table->date('registration_open_until')->nullable();
            $table->unsignedInteger('total_distance_km')->default(35);
            $table->enum('status', ['draft', 'registration_open', 'registration_closed', 'in_progress', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanghs');
    }
};
