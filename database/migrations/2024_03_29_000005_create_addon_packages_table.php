<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('addon_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('meal_limit');
            $table->integer('serving_size');
            $table->decimal('price', 10, 2);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_packages');
    }
};
