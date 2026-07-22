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
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_attended')->default(false)->after('status');
            $table->timestamp('attended_at')->nullable()->after('is_attended');
            $table->string('certificate_code')->nullable()->unique()->after('attended_at');
            $table->timestamp('certificate_sent_at')->nullable()->after('certificate_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_attended', 'attended_at', 'certificate_code', 'certificate_sent_at']);
        });
    }
};
