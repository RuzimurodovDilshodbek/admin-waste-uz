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
        Schema::create('management_persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('full_name_uz')->nullable();
            $table->string('full_name_kr')->nullable();
            $table->string('full_name_ru')->nullable();
            $table->string('full_name_en')->nullable();

            $table->string('position_name_uz')->nullable();
            $table->string('position_name_kr')->nullable();
            $table->string('position_name_ru')->nullable();
            $table->string('position_name_en')->nullable();

            $table->longText('about_uz')->nullable();
            $table->longText('about_kr')->nullable();
            $table->longText('about_ru')->nullable();
            $table->longText('about_en')->nullable();


            $table->longText('tasks_uz')->nullable();
            $table->longText('tasks_kr')->nullable();
            $table->longText('tasks_ru')->nullable();
            $table->longText('tasks_en')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();


            $table->longText('address_uz')->nullable();
            $table->longText('address_kr')->nullable();
            $table->longText('address_ru')->nullable();
            $table->longText('address_en')->nullable();

            $table->longText('work_time_uz')->nullable();
            $table->longText('work_time_kr')->nullable();
            $table->longText('work_time_ru')->nullable();
            $table->longText('work_time_en')->nullable();


            $table->string('type')->default('management');

            $table->integer('sort')->nullable();
            $table->boolean('status')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_persons');
    }
};
