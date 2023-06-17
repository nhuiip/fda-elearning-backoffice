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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lessonId')->unsigned();
            $table->foreign('lessonId')->references('id')->on('lessons')->onDelete('cascade');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->boolean('hasImage')->default(0);
            $table->string('imageUrl')->nullable();
            $table->integer('score')->comment('คะแนน')->default(0);
            $table->integer('sort')->comment('ลำดับการแสดงผล');
            $table->boolean('status')->comment('0 = inactive, 1 = active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
