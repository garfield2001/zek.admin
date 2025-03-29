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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('menu_limit')->comment('Number of meals customer can select');
            $table->integer('minimum_guests')->comment('Minimum number of guests for this package');
            $table->integer('maximum_guests')->comment('Maximum number of guests for this package');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
