<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Add a plain index on sangh_id so MySQL can use it for the FK
            // before we drop the composite unique that was previously covering it
            $table->index('sangh_id', 'registrations_sangh_id_index');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropUnique('registrations_sangh_id_mobile_unique');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->unique(['sangh_id', 'mobile']);
            $table->dropIndex('registrations_sangh_id_index');
        });
    }
};
