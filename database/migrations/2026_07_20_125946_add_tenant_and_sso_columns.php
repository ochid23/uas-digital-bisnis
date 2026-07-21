<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modifikasi tabel events untuk Multi-Tenant
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('organizer_id')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // Modifikasi tabel users untuk SSO Google
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable();
            $table->string('password')->nullable()->change(); // Password boleh kosong jika via SSO
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['organizer_id']);
            $table->dropColumn('organizer_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->string('password')->nullable(false)->change();
        });
    }
};