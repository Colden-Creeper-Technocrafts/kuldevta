<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_gu');
            $table->text('description_en')->nullable();
            $table->text('description_gu')->nullable();
            $table->enum('event_type', ['havan', 'monthly_havan', 'sangh', 'special'])->default('special');
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('venue_en')->nullable();
            $table->string('venue_gu')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
