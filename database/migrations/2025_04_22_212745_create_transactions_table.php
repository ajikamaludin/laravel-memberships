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
        Schema::create('transactions', function (Blueprint $table) {
            // id
            $table->ulid('id')->primary();

            // relations
            $table->ulid('member_id')->nullable();
            $table->ulid('account_id')->nullable();

            // columns
            $table->timestamp('transaction_date')->nullable();
            $table->decimal('amount', 24, 2)->nullable();
            $table->decimal('discount', 24, 2)->nullable();

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
        Schema::dropIfExists('transactions');
    }
};
