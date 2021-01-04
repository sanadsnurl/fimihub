<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('vehicle_number')->nullable();
            $table->string('model_name')->nullable();
            $table->string('vehicle_image')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('color')->nullable();
            $table->string('id_proof')->nullable();
            $table->text('address')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('background_check')->nullable();
            $table->string('food_permit')->nullable();
            $table->string('policy_company')->nullable();
            $table->string('insurance_company')->nullable();
            $table->string('insurance_start_date')->nullable();
            $table->string('insurance_end_date')->nullable();
            $table->string('dl_start_date')->nullable();
            $table->string('dl_end_date')->nullable();
            $table->string('registraion_start_date')->nullable();
            $table->string('registraion_end_date')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
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
        Schema::dropIfExists('vehicle_details');
    }
}
