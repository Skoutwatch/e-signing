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
        Schema::table('document_participants', function (Blueprint $table) {
            $table->integer('approval_sequence_order')->default(false)->after('status');
            $table->integer('signing_sequence_order')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_participants', function (Blueprint $table) {
            $table->dropColumn('approval_sequence_order');
            $table->dropColumn('signing_sequence_order');
        });
    }
};
