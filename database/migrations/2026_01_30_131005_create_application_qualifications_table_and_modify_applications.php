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
        Schema::create('application_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('institution');
            $table->string('year'); // Kept as string for flexibility or use year()
            $table->timestamps();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('highest_qualification')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('highest_qualification')->nullable(false)->change();
        });

        Schema::dropIfExists('application_qualifications');
    }
};
