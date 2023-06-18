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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('questionId')->unsigned();
            $table->foreign('questionId')->references('id')->on('questions')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->boolean('hasImage')->default(0);
            $table->string('imageUrl')->nullable();
            $table->boolean('isRight')->comment('0 = ไม่ถูก, 1 = ถูกต้อง')->default(0);
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
        Schema::dropIfExists('choices');
    }
};
