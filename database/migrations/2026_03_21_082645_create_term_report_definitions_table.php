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
        Schema::create('term_report_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('schoolID');
            $table->year('year');
            $table->string('term');
            $table->json('exam_ids'); // Multi-selection of exam IDs
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Unique constraint to prevent multiple definitions for same school, year, term
            $table->unique(['schoolID', 'year', 'term'], 'school_year_term_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('term_report_definitions');
    }
};
