<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_menutypes', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_type_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->primary(['package_id', 'menu_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_menutypes');
    }
};
