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
        Schema::create('phone_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('users');
            $table->foreignId('deal_id')->nullable()->constrained('deals');
            $table->foreignId('manager_id')->nullable()->constrained('managers');
            $table->string('phone');
            $table->text('comment')->nullable();
            $table->longText('transcription')->nullable();
            $table->json('ai_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_calls');
    }
};
