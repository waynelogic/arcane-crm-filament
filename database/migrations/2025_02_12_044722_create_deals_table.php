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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->uuid('number')->nullable()->unique();
            $table->foreignId('parent_id')->nullable()->constrained('deals')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('manager_id')->constrained('managers');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->decimal('total_discount', 12, 2)->nullable();
            $table->json('product_items')->nullable();
            $table->boolean('auto_price')->default(false);
            $table->boolean('completed')->default(false);
            $table->boolean('canceled')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
