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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid('external_id')->nullable()->unique();
            $table->foreignId('manager_id')->nullable()->constrained('managers');
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('inn', 12)->nullable();
            $table->string('kpp', 9)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_customer')->default(false);
            $table->boolean('is_supplier')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('position')->nullable();
            $table->string('role')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_users');
        Schema::dropIfExists('companies');
    }
};
