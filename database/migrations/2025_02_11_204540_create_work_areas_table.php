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
        Schema::create('work_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('hour_price', 12, 2)->default(0);
            $table->string('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('work_areas_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('managers');
            $table->foreignId('work_area_id')->constrained('work_areas');
            $table->tinyInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['manager_id', 'work_area_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_areas_managers');
        Schema::dropIfExists('work_areas');
    }
};
