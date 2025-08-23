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
        Schema::table('tutor_opinions', function (Blueprint $table) {
            $table->renameColumn('short_title', 'short_title_uz');
            $table->string('short_title_kr')->nullable();
            $table->string('short_title_ru')->nullable();
            $table->string('short_title_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_opinions', function (Blueprint $table) {
            //
        });
    }
};
