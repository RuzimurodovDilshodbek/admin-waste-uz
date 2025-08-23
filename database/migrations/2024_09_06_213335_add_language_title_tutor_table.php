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
        Schema::table('tutors', function (Blueprint $table) {
            $table->renameColumn('firstname', 'first_name_uz');
            $table->string('first_name_kr')->nullable();
            $table->string('first_name_ru')->nullable();
            $table->string('first_name_en')->nullable();


            $table->renameColumn('lastname', 'last_name_uz');
            $table->string('last_name_kr')->nullable();
            $table->string('last_name_ru')->nullable();
            $table->string('last_name_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
