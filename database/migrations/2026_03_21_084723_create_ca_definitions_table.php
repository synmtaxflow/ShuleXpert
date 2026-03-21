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
        Schema::create('ca_definitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schoolID');
            $table->integer('year');
            $table->string('term');
            $table->unsignedBigInteger('examID'); // The school exam being defined with CA
            $table->json('test_ids'); // Array of test examIDs
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('schoolID')->references('schoolID')->on('schools')->onDelete('cascade');
            $table->foreign('examID')->references('examID')->on('examinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ca_definitions');
    }
};
