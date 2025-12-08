<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_dihold_to_slot_waktu_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('slot_waktu', function (Blueprint $t) {
            $t->unsignedInteger('dihold')->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('slot_waktu', function (Blueprint $t) {
            $t->dropColumn('dihold');
        });
    }
};
