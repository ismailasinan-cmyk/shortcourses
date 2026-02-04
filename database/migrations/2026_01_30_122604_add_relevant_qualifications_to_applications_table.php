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
            $table->string('ssce_file_path')->nullable()->change();
            $table->string('relevant_qualifications_file_path')->nullable()->after('ssce_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('ssce_file_path')->nullable(false)->change();
            $table->dropColumn('relevant_qualifications_file_path');
        });
    }
};
