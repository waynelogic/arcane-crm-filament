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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->uuid('external_id')->unique()->nullable();
            // Attributes
            $table->boolean('active')->default(true);
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['service', 'product'])->default('product');
            $table->decimal('price', 12, 2)->default(0);

            // Учет
            $table->string('code')->unique()->nullable();
            $table->string('sku')->nullable();

            $table->json('offers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
