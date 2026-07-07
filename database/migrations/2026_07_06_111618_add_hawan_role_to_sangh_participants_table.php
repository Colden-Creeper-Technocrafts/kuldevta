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
        Schema::table('sangh_participants', function (Blueprint $table) {
            $table->enum('hawan_role', ['main', 'support_1', 'support_2', 'support_3', 'support_4'])
                  ->nullable()
                  ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('sangh_participants', function (Blueprint $table) {
            $table->dropColumn('hawan_role');
        });
    }
};
