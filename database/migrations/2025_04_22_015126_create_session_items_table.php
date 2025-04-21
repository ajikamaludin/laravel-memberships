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
        Schema::create('subject_session_items', function (Blueprint $table) {
            // id
            $table->ulid('id')->primary();

            // relations
            $table->ulid('session_id')->nullable();
            $table->ulid('member_id')->nullable();
            $table->ulid('membership_id')->nullable();

            // columns

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
        Schema::dropIfExists('subject_session_items');
    }
};
