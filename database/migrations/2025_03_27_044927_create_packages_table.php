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
            $table->integer('meal_limit')->comment('Number of meals customer can select');
            $table->integer('minimum_guests')->comment('Minimum number of guests for this package');
            $table->integer('maximum_guests')->comment('Maximum number of guests for this package');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Create inclusions table
        Schema::create('inclusions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create pivot table
        Schema::create('inclusion_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('inclusion_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['package_id', 'inclusion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inclusion_package');
        Schema::dropIfExists('inclusions');
        Schema::dropIfExists('packages');
    }
};
