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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('teacher_id')->constrained()->nullable()->onDelete('cascade');;
            $table->bigInteger('Category_id')->constrained()->nullable()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
};
