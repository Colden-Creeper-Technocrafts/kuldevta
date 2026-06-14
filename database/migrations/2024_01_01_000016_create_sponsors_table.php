<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->morphs('sponsorable');
            $table->string('name');
            $table->string('mobile', 15)->nullable();
            $table->string('village_city')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('sponsor_type', ['main', 'gold', 'silver', 'general'])->default('general');
            $table->string('description_en')->nullable();
            $table->string('description_gu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
