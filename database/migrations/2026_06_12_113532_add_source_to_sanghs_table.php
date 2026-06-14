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
        Schema::table('sanghs', function (Blueprint $table) {
            $table->string('source_en')->nullable()->after('event_id');
            $table->string('source_gu')->nullable()->after('source_en');
        });
    }

    public function down(): void
    {
        Schema::table('sanghs', function (Blueprint $table) {
            $table->dropColumn(['source_en', 'source_gu']);
        });
    }
};
