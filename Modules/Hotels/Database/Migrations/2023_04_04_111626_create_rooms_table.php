<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            //Info
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();

            //Price
            $table->decimal('syrian_price', 12, 2)->nullable();
            $table->decimal('foreign_price', 12, 2)->nullable();

            $table->integer('number')->nullable();
            $table->integer('beds')->nullable();
            $table->integer('size')->nullable();
            $table->integer('baths')->nullable();
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();

            //Extra Info
            $table->tinyInteger('status')->default(true);
            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->unsignedBigInteger('hotel_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
