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
        Schema::create('core_terms', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('content',255)->nullable();
            $table->string('slug',255)->nullable();
            $table->unsignedBigInteger('core_attribute_id');
            $table->text('icon_name')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('core_attribute_id')->references('id')->on('core_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_terms');
    }
};
