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
        Schema::table('trainings', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->string('training_institute')->nullable(); 
            $table->json('attachments')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->foreignId('state_id');
            $table->string('training_institute');
            $table->json('attachments');
        });
    }
};