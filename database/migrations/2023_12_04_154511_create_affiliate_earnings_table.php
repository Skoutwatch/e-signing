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
        Schema::create('affiliate_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Affiliate::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->morphs('payable');
            $table->foreignIdFor(App\Models\User::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->float('amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_earnings');
    }
};
