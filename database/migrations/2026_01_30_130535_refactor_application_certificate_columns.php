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
        Schema::table('applications', function (Blueprint $table) {
            // Drop file upload path
            $table->dropColumn(['ssce_file_path', 'relevant_qualifications_file_path']);

            // Make SSCE fields nullable as they might not be relevant for Degree holders
            $table->string('ssce_type')->nullable()->change();
            $table->year('ssce_year')->nullable()->change();
            $table->string('ssce_exam_number')->nullable()->change();

            // Add new Qualification Fields
            $table->string('highest_qualification')->after('lga'); // SSCE, Degree, etc.
            
            // Degree specific fields
            $table->string('degree_institution')->nullable()->after('highest_qualification');
            $table->string('degree_year')->nullable()->after('degree_institution');
            $table->string('degree_class')->nullable()->after('degree_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('ssce_file_path')->nullable(); // Re-add column
            $table->string('relevant_qualifications_file_path')->nullable(); 

            // Revert Nullable
            $table->string('ssce_type')->nullable(false)->change();
            $table->year('ssce_year')->nullable(false)->change();
            $table->string('ssce_exam_number')->nullable(false)->change();

            // Drop new columns
            $table->dropColumn(['highest_qualification', 'degree_institution', 'degree_year', 'degree_class']);
        });
    }
};
