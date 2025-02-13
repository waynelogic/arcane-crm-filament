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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('external_id')->nullable()->unique();
            // Relationships
            $table->foreignId('manager_id')->constrained('managers');
            $table->foreignId('deal_id')->nullable()->constrained('deals')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->foreignId('work_area_id')->nullable()->constrained('work_areas')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            // Fields
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->string('status')->default('new');
            $table->boolean('completed')->default(false);
            $table->boolean('important')->default(false);
            $table->boolean('urgent')->default(false);
            $table->timestamp('deadline')->nullable();
            $table->decimal('hour_price', 12, 2)->nullable();
            // Work time
            $table->string('work_status')->nullable();
            $table->float('hours')->nullable();
            $table->float('work_time')->nullable();
            $table->timestamp('played_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
