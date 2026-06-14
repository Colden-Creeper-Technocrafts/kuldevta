<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sanghs', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'title_gu',
                'description_en',
                'description_gu',
                'start_date',
                'start_time',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('sanghs', function (Blueprint $table) {
            $table->string('title_en')->after('year');
            $table->string('title_gu')->after('title_en');
            $table->text('description_en')->nullable()->after('title_gu');
            $table->text('description_gu')->nullable()->after('description_en');
            $table->date('start_date')->after('description_gu');
            $table->time('start_time')->default('05:00:00')->after('start_date');
        });
    }
};
