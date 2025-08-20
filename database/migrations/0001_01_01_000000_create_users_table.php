<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('nip')->unique();
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'guru', 'kepsek']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('no_telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('foto_profil')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('presensi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->enum('status', ['hadir', 'hadir-dl', 'tidak-hadir', 'hadir-tidak-lapor-pulang'])->nullable();
            $table->string('latitude_masuk')->nullable();
            $table->string('longitude_masuk')->nullable();
            $table->string('latitude_keluar')->nullable();
            $table->string('longitude_keluar')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('dinas_luar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
