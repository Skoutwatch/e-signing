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
            $table->integer('sequence_order')->default(false)->after('status');
            $table->integer('comment')->nullable()->after('sequence_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_participants', function (Blueprint $table) {
            $table->dropColumn(['sequence_order', 'comment']);
        });
    }
};
