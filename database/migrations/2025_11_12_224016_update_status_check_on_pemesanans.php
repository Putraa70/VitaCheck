<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') return;

        // 1) Drop constraint lama kalau masih ada
        DB::statement('ALTER TABLE pemesanans DROP CONSTRAINT IF EXISTS cek_status_pemesanans');

        // 2) Normalisasi data lama ke skema status baru
        //    - menunggu        -> menunggu_bayar
        //    - terkonfirmasi   -> terbayar
        //    - NULL / kosong   -> menunggu_bayar
        DB::statement("UPDATE pemesanans SET status = 'menunggu_bayar' WHERE status IS NULL OR status = 'menunggu'");
        DB::statement("UPDATE pemesanans SET status = 'terbayar'        WHERE status = 'terkonfirmasi'");

        // (opsional) kalau ada status lain di luar daftar, kamu bisa mapping juga di sini
        // Misal: pending -> menunggu_bayar, paid -> terbayar, dll.

        // 3) Pasang CHECK baru (ketat, hanya status baru)
        DB::statement(<<<SQL
            ALTER TABLE pemesanans
            ADD CONSTRAINT cek_status_pemesanans
            CHECK (status IN (
                'menunggu_bayar',
                'terbayar',
                'check_in',
                'selesai',
                'dibatalkan',
                'kedaluwarsa'
            ));
        SQL);
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') return;

        DB::statement('ALTER TABLE pemesanans DROP CONSTRAINT IF EXISTS cek_status_pemesanans');

        // Kembalikan CHECK lama (kalau mau mundur)
        DB::statement(<<<SQL
            ALTER TABLE pemesanans
            ADD CONSTRAINT cek_status_pemesanans
            CHECK (status IN (
                'menunggu',
                'terkonfirmasi',
                'check_in',
                'selesai',
                'dibatalkan'
            ));
        SQL);
    }
};
