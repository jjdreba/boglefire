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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->string('name');
            $table->string('type');
            $table->string('exchange')->nullable();
            $table->string('currency')->default('USD');
            $table->decimal('last_price', 12, 4)->nullable();
            $table->timestamp('last_price_updated_at')->nullable();
            $table->timestamps();

            // Create unique constraint on user_id and symbol
            $table->unique(['user_id', 'symbol']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
