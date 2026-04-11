<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('training_applications');

        Schema::create('training_applications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            
            $table->string('nominee_emp_id');     
            $table->string('nominee_name');       
            $table->string('nominee_designation'); 
            $table->string('nominee_email');      
            $table->string('nominee_phone');      
            
            $table->boolean('is_self_apply')->default(false); 
            $table->string('centre')->nullable(); 
            $table->string('year')->nullable();
            $table->string('status')->default('submitted'); 
            
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_applications');
    }
};