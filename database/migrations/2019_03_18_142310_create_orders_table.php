<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //"driver_id","restaurant_id","customer_id","address","total","status","picked_at"
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("driver_id")->unsigned()->nullable();
            $table->bigInteger("restaurant_id")->unsigned();
            $table->bigInteger("customer_id")->unsigned();
            $table->string("address");
            $table->enum("status",[1,2,3,4]);
            $table->dateTime("picked_at")->nullable();
            $table->double("total",8,2);
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
