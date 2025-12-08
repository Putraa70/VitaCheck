<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $t) {
            if (!Schema::hasColumn('pemesanans', 'total_bayar')) {
                $t->unsignedBigInteger('total_bayar')->default(0)->after('status');
            }
            if (!Schema::hasColumn('pemesanans', 'kedaluwarsa_pada')) {
                $t->timestamp('kedaluwarsa_pada')->nullable()->after('total_bayar');
            }
            if (!Schema::hasColumn('pemesanans', 'nomor_antrian')) {
                $t->unsignedInteger('nomor_antrian')->nullable()->after('kedaluwarsa_pada');
            }
            if (!Schema::hasColumn('pemesanans', 'waktu_tes')) {
                $t->timestamp('waktu_tes')->nullable()->after('nomor_antrian');
            }
            if (!Schema::hasColumn('pemesanans', 'qr_checkin')) {
                $t->string('qr_checkin')->nullable()->after('waktu_tes');
            }
        });

        // (opsional) index gabungan untuk query antrian per slot
        Schema::table('pemesanans', function (Blueprint $t) {
            $t->index(
                ['slot_waktu_id', 'nomor_antrian'],
                'pemesanans_slot_waktu_nomor_antrian_index'
            );
        });

        // Update constraint status
        try {
            DB::statement("ALTER TABLE pemesanans DROP CONSTRAINT IF EXISTS cek_status_pemesanans");
        } catch (\Throwable $e) {
        }

        try {
            DB::statement("ALTER TABLE pemesanans
                ADD CONSTRAINT cek_status_pemesanans
                CHECK (status IN (
                    'menunggu',
                    'menunggu_bayar',
                    'terbayar',
                    'terkonfirmasi',
                    'check_in',
                    'selesai',
                    'dibatalkan',
                    'kedaluwarsa'
                ))");
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $t) {
            $drops = ['qr_checkin', 'waktu_tes', 'nomor_antrian', 'kedaluwarsa_pada', 'total_bayar'];
            foreach ($drops as $col) {
                if (Schema::hasColumn('pemesanans', $col)) {
                    $t->dropColumn($col);
                }
            }
        });

        try {
            DB::statement("ALTER TABLE pemesanans DROP CONSTRAINT IF EXISTS cek_status_pemesanans");
            DB::statement("ALTER TABLE pemesanans
                ADD CONSTRAINT cek_status_pemesanans
                CHECK (status IN ('menunggu','terkonfirmasi','check_in','selesai','dibatalkan'))");
        } catch (\Throwable $e) {
        }
    }
};
