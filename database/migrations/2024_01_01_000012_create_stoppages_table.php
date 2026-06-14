<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stoppages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sangh_id')->constrained()->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_gu');
            $table->string('address_en')->nullable();
            $table->string('address_gu')->nullable();
            $table->unsignedInteger('km_marker')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('has_water')->default(true);
            $table->boolean('has_food')->default(false);
            $table->boolean('has_tea')->default(true);
            $table->boolean('has_medical')->default(false);
            $table->boolean('has_rest')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stoppages');
    }
};
