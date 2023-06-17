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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('videoUrl')->nullable();
            $table->integer('passScore')->comment('เกณฑ์คะแนนที่ผ่าน หน่วยเป็น %');
            $table->integer('sort')->comment('ลำดับการแสดงผล');
            $table->integer('status')->comment('0 = inactive, 1 = active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
