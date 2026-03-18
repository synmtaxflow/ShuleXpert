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
        // Modify paper_approval_chains
        Schema::table('paper_approval_chains', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        
        Schema::table('paper_approval_chains', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
            $table->enum('special_role_type', ['class_teacher', 'coordinator'])->nullable()->after('role_id');
        });

        Schema::table('paper_approval_chains', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Modify paper_approval_logs
        Schema::table('paper_approval_logs', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::table('paper_approval_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
            $table->enum('special_role_type', ['class_teacher', 'coordinator'])->nullable()->after('role_id');
        });

        Schema::table('paper_approval_logs', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paper_approval_chains', function (Blueprint $table) {
            $table->dropColumn('special_role_type');
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
        });

        Schema::table('paper_approval_logs', function (Blueprint $table) {
            $table->dropColumn('special_role_type');
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
        });
    }
};
