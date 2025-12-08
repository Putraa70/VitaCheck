<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_columns_to_pemesanans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $t) {
            $t->string('status')->default('menunggu_bayar')->change(); // jika kolom sudah ada, ini menyesuaikan default
            $t->timestamp('kedaluwarsa_pada')->nullable();
            $t->unsignedInteger('total_bayar')->default(0);
            $t->string('metode_bayar')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $t) {
            $t->dropColumn(['kedaluwarsa_pada', 'total_bayar', 'metode_bayar']);
            // tidak perlu revert default 'status' jika tidak aman
        });
    }
};
