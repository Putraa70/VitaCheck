<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanans', 'qr_checkin')) {
                $table->string('qr_checkin')->nullable()->after('waktu_tes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanans', 'qr_checkin')) {
                $table->dropColumn('qr_checkin');
            }
        });
    }
};
