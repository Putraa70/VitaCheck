<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('kode')->unique();
            $t->foreignUuid('pengguna_id')->constrained('users')->cascadeOnDelete();
            $t->foreignUuid('jenis_tes_id')->constrained('jenis_tes')->cascadeOnDelete();
            $t->foreignUuid('slot_waktu_id')->constrained('slot_waktu')->cascadeOnDelete();
            $t->string('status'); // menunggu|terkonfirmasi|check_in|selesai|dibatalkan
            $t->timestamp('dibayar_pada')->nullable();
            $t->timestamps();
        });

        DB::statement("ALTER TABLE pemesanans
            ADD CONSTRAINT cek_status_pemesanans
            CHECK (status IN ('menunggu','terkonfirmasi','check_in','selesai','dibatalkan'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
