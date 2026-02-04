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
        Schema::create('short_courses', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('course_name');
            $table->string('code')->unique();
            $table->decimal('fee', 10, 2);
            $table->string('duration');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_courses');
    }
};
