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
            $table->decimal('application_fee_amount', 10, 2)->after('amount')->default(5000.00);
            $table->string('application_fee_status')->after('application_fee_amount')->default('PENDING');
            $table->string('course_fee_status')->after('payment_status')->default('PENDING');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type')->after('amount')->nullable(); // APPLICATION_FEE, COURSE_FEE, BOTH
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['application_fee_amount', 'application_fee_status', 'course_fee_status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });
    }
};
