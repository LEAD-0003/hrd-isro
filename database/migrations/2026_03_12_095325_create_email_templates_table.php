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
        Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                // Password Reset
                $table->string('reset_title')->default('Password Reset Request');
                $table->string('reset_color')->default('#0a192f');
                $table->text('reset_body');

                // Training Published
                $table->string('pub_title')->default('New Training Notification');
                $table->string('pub_color')->default('#1e3a8a');
                $table->text('pub_body');

                // Training Nominated
                $table->string('nom_title')->default('Nomination Received');
                $table->string('nom_color')->default('#0284c7');
                $table->text('nom_body');

                // Training Approved
                $table->string('app_title')->default('Nomination Approved');
                $table->string('app_color')->default('#059669');
                $table->text('app_body');

                // Feedback Reminder
                $table->string('rem_title')->default('Training Completed!');
                $table->string('rem_color')->default('#0284c7');
                $table->text('rem_body');

                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};