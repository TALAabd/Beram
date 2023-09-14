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
        Schema::create('resturants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('title', 255);
            $table->string('slug', 255)->charset('utf8')->index();
            $table->text('content')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->string('address', 255)->nullable();
            $table->string('map_lat', 20)->nullable();
            $table->string('map_lng', 20)->nullable();
            $table->integer('map_zoom')->nullable();
            $table->tinyInteger('is_featured')->default(false);
            $table->text('policy')->nullable();
            $table->smallInteger('star_rate')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->tinyInteger('status')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('update_user')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resturants');
    }
};
