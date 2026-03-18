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
        // Add option to examinations table for paper approval
        Schema::table('examinations', function (Blueprint $table) {
            if (!Schema::hasColumn('examinations', 'use_paper_approval')) {
                $table->boolean('use_paper_approval')->default(false)->after('upload_paper');
            }
        });

        // Add paper_approval_step to exam_papers table
        Schema::table('exam_papers', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_papers', 'current_approval_order')) {
                $table->unsignedInteger('current_approval_order')->default(1)->after('status');
            }
        });

        // Create Paper Approval Chain Configuration Table
        if (!Schema::hasTable('paper_approval_chains')) {
            Schema::create('paper_approval_chains', function (Blueprint $table) {
                $table->id('paper_approval_chainID');
                $table->foreignId('examID')->constrained('examinations', 'examID')->onDelete('cascade');
                $table->unsignedBigInteger('role_id')->comment('Role ID linked to roles table');
                $table->unsignedInteger('approval_order')->comment('Order of approval (1, 2, 3...)');
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->unique(['examID', 'approval_order'], 'unique_paper_chain_order');
            });
        }

        // Create Paper Approval Logs for individual papers
        if (!Schema::hasTable('paper_approval_logs')) {
            Schema::create('paper_approval_logs', function (Blueprint $table) {
                $table->id('paper_approval_logID');
                $table->foreignId('exam_paperID')->constrained('exam_papers', 'exam_paperID')->onDelete('cascade');
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->unsignedInteger('approval_order');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('teachers')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropColumn('use_paper_approval');
        });

        Schema::table('exam_papers', function (Blueprint $table) {
            $table->dropColumn('current_approval_order');
        });

        Schema::dropIfExists('paper_approval_logs');
        Schema::dropIfExists('paper_approval_chains');
    }
};
