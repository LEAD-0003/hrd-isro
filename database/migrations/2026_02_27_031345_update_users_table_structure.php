<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->dropColumn(['centre', 'designation']); 
            
            $table->foreignId('designation_id')->nullable()->after('employee_code')->constrained('designations')->onDelete('set null');
            $table->foreignId('centre_id')->nullable()->after('designation_id')->constrained('centres')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['designation_id']);
            $table->dropForeign(['centre_id']);
            $table->dropColumn(['designation_id', 'centre_id']);
            $table->string('designation')->nullable();
            $table->string('centre')->nullable();
        });
    }
};