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
        Schema::create('members', function (Blueprint $table) {
            // id
            $table->ulid('id')->primary();

            // relations
            $table->ulid('member_category_id')->nullable();

            // columns
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();

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
        Schema::dropIfExists('members');
    }
};
