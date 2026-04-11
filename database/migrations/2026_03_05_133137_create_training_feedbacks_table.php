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
        Schema::create('training_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_application_id')->constrained()->onDelete('cascade');

            $table->integer('clarity_objectives');
            $table->integer('relevance_to_role');
            $table->integer('quality_materials');
            $table->integer('practical_applicability');

            $table->integer('subject_knowledge');
            $table->integer('clarity_communication');
            $table->integer('engagement_interaction');
            $table->integer('time_management');

            $table->integer('venue_platform_quality');
            $table->integer('organization_coordination');
            $table->integer('accommodation_boarding');
            $table->integer('transportation');

            $table->integer('met_expectations');
            $table->integer('overall_rating'); 

            $table->text('most_useful_aspect');
            $table->text('areas_for_improvement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_feedbacks');
    }
};
