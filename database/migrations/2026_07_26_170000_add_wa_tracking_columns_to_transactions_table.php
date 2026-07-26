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
        if (!Schema::hasColumn('transactions', 'wa_sent_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->timestamp('wa_sent_at')->nullable();
            });
        }

        if (!Schema::hasColumn('transactions', 'wa_reminder_sent_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->timestamp('wa_reminder_sent_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['wa_sent_at', 'wa_reminder_sent_at']);
        });
    }
};
