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
        Schema::table('training_applications', function (Blueprint $table) {

            $table->text('hq_comments')->nullable();
            $table->integer('feedback_rating')->nullable(); 
            $table->text('feedback_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_applications', function (Blueprint $table) {
            $table->dropColumn(['hq_comments', 'feedback_rating', 'feedback_comments']);
        });
    }
};
