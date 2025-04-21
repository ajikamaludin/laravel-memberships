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
        Schema::create('journals', function (Blueprint $table) {
            // id
            $table->ulid('id')->primary();

            // relations
            $table->ulid('account_id')->primary();

            // columns
            $table->string('type')->nullable();
            $table->decimal('amount', 24, 2)->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->text('description')->nullable();
            $table->string('model')->nullable();
            $table->string('model_id')->nullable();

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
        Schema::dropIfExists('journals');
    }
};
