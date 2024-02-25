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
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('has_reminder')->nullable()->after('status');
            $table->string('reminder_frequency')->nullable()->after('has_reminder');
            $table->boolean('has_signing_sequence')->nullable()->after('reminder_frequency');
            $table->boolean('has_approval_sequence')->nullable()->after('has_signing_sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('has_reminder')->nullable()->after('status');
            $table->dropColumn('reminder_frequency')->nullable()->after('has_reminder');
            $table->dropColumn('has_signing_sequence')->nullable()->after('reminder_frequency');
            $table->dropColumn('has_approval_sequence')->nullable()->after('has_signing_sequence');
        });
    }
};
