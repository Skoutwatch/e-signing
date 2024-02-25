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
        Schema::table('document_resources', function (Blueprint $table) {
            $table->string('type')->nullable()->after('tool_pos_left');
            $table->string('category')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_resources', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('category');
        });
    }
};
