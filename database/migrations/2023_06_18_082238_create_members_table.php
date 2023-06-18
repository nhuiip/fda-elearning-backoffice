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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('businessType')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->comment('รหัสผ่านแบบเข้ารหัส');
            $table->string('rawPassword')->comment('รหัสผ่านแบบไม่เข้ารหัส');
            $table->boolean('notified')->default(0);
            $table->boolean('passed')->default(0);
            $table->dateTime('registerDate');
            $table->dateTime('firstLoginDate')->nullable();
            $table->dateTime('lastVisitDate')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
