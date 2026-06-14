<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sangh_id')->constrained()->cascadeOnDelete();
            $table->string('token', 12)->unique();
            $table->string('name');
            $table->string('mobile', 15);
            $table->string('village')->nullable();
            $table->string('city')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_mobile', 15)->nullable();
            $table->string('group_name')->nullable();
            $table->boolean('is_group_leader')->default(false);
            $table->foreignId('group_leader_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->enum('status', ['registered', 'confirmed', 'completed', 'dropped'])->default('registered');
            $table->enum('registered_by', ['self', 'admin'])->default('self');
            $table->timestamp('confirmed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['sangh_id', 'mobile']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
