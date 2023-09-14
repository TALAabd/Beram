<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('email')->unique();
            $table->string('password')->min(6);
            $table->string('address', 255)->nullable();
            $table->string('phone')->unique();
            $table->date('birthday')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->integer('zip_code')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('role');
            $table->tinyInteger('status')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('fcm_token', 255)->nullable(); //new
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
