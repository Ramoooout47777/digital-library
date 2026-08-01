<?php
// database/migrations/xxxx_xx_xx_create_print_settings_table.php

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
        Schema::create('print_settings', function (Blueprint $table) {
            $table->id();
            
            // Print Type
            $table->string('print_type')->default('digital')->comment('digital, offset, screen');
            $table->string('paper_size')->default('A4')->comment('A4, A3, A5, Letter, Legal');
            $table->string('paper_type')->nullable()->comment('glossy, matte, recycled');
            $table->string('print_quality')->default('high')->comment('high, medium, low');
            
            // Print Options
            $table->integer('copies')->default(1);
            $table->string('color_mode')->default('color')->comment('color, black_white');
            $table->boolean('double_sided')->default(true);
            $table->boolean('binding')->default(false);
            $table->string('binding_type')->nullable()->comment('perfect, saddle, spiral, case');
            
            // Pricing
            $table->decimal('price_per_page', 10, 2)->default(0.05);
            $table->decimal('setup_fee', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            
            // Additional
            $table->json('custom_options')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_settings');
    }
};