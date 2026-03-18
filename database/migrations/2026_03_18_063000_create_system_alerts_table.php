<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schoolID');
            $table->string('target_user_type');

            $table->unsignedBigInteger('target_role_id')->nullable();
            $table->unsignedBigInteger('target_profession_id')->nullable();
            $table->boolean('applies_to_all')->default(false);

            $table->string('alert_type')->default('info');
            $table->text('message');

            $table->boolean('is_marquee')->default(false);
            $table->string('width')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index(['schoolID', 'target_user_type', 'is_active']);
            $table->index(['target_role_id']);
            $table->index(['target_profession_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_alerts');
    }
};
