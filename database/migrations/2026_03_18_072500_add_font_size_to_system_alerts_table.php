<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('system_alerts', 'font_size')) {
            Schema::table('system_alerts', function (Blueprint $table) {
                $table->string('font_size')->nullable()->after('is_bold');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('system_alerts', 'font_size')) {
            Schema::table('system_alerts', function (Blueprint $table) {
                $table->dropColumn('font_size');
            });
        }
    }
};
