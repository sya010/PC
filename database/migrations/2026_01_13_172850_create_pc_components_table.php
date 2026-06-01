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
        Schema::create('pc_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('component_type', ['cpu', 'motherboard', 'ram', 'storage', 'gpu', 'psu', 'case']);
            $table->string('socket_type')->nullable();
            $table->string('ram_type')->nullable();
            $table->string('form_factor')->nullable();
            $table->integer('wattage')->unsigned()->nullable();
            $table->json('compatibility_rules')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_components');
    }
};
