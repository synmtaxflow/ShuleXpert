<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('system_alerts', 'is_bold')) {
            Schema::table('system_alerts', function (Blueprint $table) {
                $table->boolean('is_bold')->default(false)->after('text_color');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('system_alerts', 'is_bold')) {
            Schema::table('system_alerts', function (Blueprint $table) {
                $table->dropColumn('is_bold');
            });
        }
    }
};
