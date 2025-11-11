<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('berkas_pemesanans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pemesanan_id')->constrained('pemesanans')->cascadeOnDelete();
            $table->string('jenis'); // contoh: ktm | bukti_bayar
            $table->string('lokasi_berkas'); // path/file name di storage
            $table->timestamps();
        });

        // Constraint khusus Postgres untuk validasi nilai jenis
        DB::statement("ALTER TABLE berkas_pemesanans
            ADD CONSTRAINT cek_jenis_berkas_pemesanans
            CHECK (jenis IN ('ktm','bukti_bayar'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_pemesanans');
    }
};
