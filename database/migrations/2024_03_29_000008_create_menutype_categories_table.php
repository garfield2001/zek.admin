<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menutype_categories', function (Blueprint $table) {
            $table->foreignId('menu_type_id')->constrained()->onDelete('cascade');
            $table->enum('category', ['catering', 'addon']);
            $table->timestamps();

            $table->primary(['menu_type_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menutype_categories');
    }
};
