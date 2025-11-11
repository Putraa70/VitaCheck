<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profil_mahasiswas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengguna_id')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->foreignUuid('program_studi_id')->constrained('program_studi')->cascadeOnDelete();
            $table->string('no_hp', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_mahasiswas');
    }
};
