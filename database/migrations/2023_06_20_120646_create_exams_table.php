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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('memberId')->unsigned();
            $table->foreign('memberId')->references('id')->on('members')->onDelete('cascade');
            $table->bigInteger('lessonId')->unsigned();
            $table->foreign('lessonId')->references('id')->on('lessons')->onDelete('cascade');
            $table->integer('score')->comment('คะแนนรวมหลังทำเสร็จ');
            $table->boolean('isPass')->comment('0 = ไม่ผ่าน, 1 = ผ่าน')->default(0);
            $table->boolean('isFinish')->comment('0 = ทำยังไม่เสร็จ, 1 = ทำเสร็จแล้ว')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
