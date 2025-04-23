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
        Schema::create('employee_payment_items', function (Blueprint $table) {
            // id
            $table->ulid('id')->primary();

            // relations
            $table->ulid('employee_payment_id')->nullable();
            $table->ulid('subject_session_id')->nullable();
            $table->ulid('subject_id')->nullable();

            // columns
            $table->decimal('person_amount', 24, 2)->nullable(); // dari subject_session_items
            $table->decimal('employee_fee_per_person', 24, 2)->nullable(); // dari subject

            // default
            $table->timestamps();
            $table->softDeletes();
            $table->ulid('created_by')->nullable();
            $table->ulid('updated_by')->nullable();
            $table->ulid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payment_items');
    }
};
