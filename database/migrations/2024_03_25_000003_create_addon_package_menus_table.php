<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_package_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('addon_packages')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->boolean('is_fixed')->default(false); // if true, the menu item is fixed and cannot be changed by another menu or removed
            $table->boolean('can_change')->default(true); // if true, the menu item can be changed by another menu
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_package_menus');
    }
};
