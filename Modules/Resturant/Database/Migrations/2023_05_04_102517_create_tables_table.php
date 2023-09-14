<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('content', 255)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('number')->nullable();
            $table->integer('size')->nullable();
            $table->tinyInteger('status')->default(true);
            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->unsignedBigInteger('resturant_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('resturant_id')->references('id')->on('resturants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
};
