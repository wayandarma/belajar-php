<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the students table.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('major', 100);
            $table->unsignedSmallInteger('enrollment_year');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Drop the students table.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
