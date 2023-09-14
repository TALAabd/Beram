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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_code');
            $table->string('service_type');
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->integer('total_guests')->nullable();
            $table->string('email', 255);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->integer('phone');
            $table->text('customer_notes')->nullable();
            $table->tinyInteger('is_confirmed')->default(false);
            $table->enum('status',['Pending','Confirmed','Cancelled'])->default('Pending');
            $table->unsignedBigInteger('customer_id');
            $table->integer('bookable_id')->nullable();
            $table->string('bookable_type', 255)->nullable();
            $table->text('booking_notes')->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
