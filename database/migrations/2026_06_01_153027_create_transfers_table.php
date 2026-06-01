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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('from_facility_id')->nullable();
            $table->foreign('from_facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->unsignedBigInteger('to_facility_id')->nullable();
            $table->foreign('to_facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->enum('level', ['facility', 'district', 'region', 'ministry']);
            $table->enum('level_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
