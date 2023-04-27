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
        Schema::create('process_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('process_id')->index()->comment('外键，引用 Process 表');
            $table->json('data')->nullable()->comment('数据');
            $table->string('status')->default('')->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_instances');
    }
};
