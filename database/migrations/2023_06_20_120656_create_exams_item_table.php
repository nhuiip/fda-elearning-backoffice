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
        Schema::create('exams_item', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('examId')->unsigned();
            $table->foreign('examId')->references('id')->on('exams')->onDelete('cascade');
            $table->bigInteger('questionId')->unsigned();
            $table->foreign('questionId')->references('id')->on('questions')->onDelete('cascade');
            $table->bigInteger('choiceId')->unsigned();
            $table->foreign('choiceId')->references('id')->on('choices')->onDelete('cascade');
            $table->integer('score')->comment('คะแนนที่ได้')->default(0);
            $table->boolean('isRight')->comment('0 = ไม่ถูก, 1 = ถูกต้อง');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams_item');
    }
};
