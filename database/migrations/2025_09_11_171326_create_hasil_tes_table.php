<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pemesanan_id')->constrained('pemesanans')->cascadeOnDelete();
            $table->string('status_hasil')->default('menunggu'); // negatif | positif | normal | abnormal | menunggu
            $table->text('catatan')->nullable(); // catatan dokter/klinik
            $table->string('berkas_hasil')->nullable(); // path file hasil tes
            $table->timestamps();
        });

        DB::statement("ALTER TABLE hasil_tes
            ADD CONSTRAINT cek_status_hasil
            CHECK (status_hasil IN ('negatif','positif','normal','abnormal','menunggu'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_tes');
    }
};
