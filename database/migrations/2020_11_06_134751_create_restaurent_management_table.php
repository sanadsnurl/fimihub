<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurentManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurent_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('resto_id');
            $table->string('name')->nullable();
            $table->text('about')->nullable();
            $table->text('other_details')->nullable();
            $table->string('official_number')->nullable();
            $table->string('picture')->nullable();
            $table->decimal('avg_cost', 8, 2)->nullable();
            $table->string('avg_time')->nullable();
            $table->string('open_time')->nullable();
            $table->string('close_time')->nullable();
            $table->string('address')->nullable();
            $table->decimal('delivery_charge', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->string('pincode')->nullable();
            $table->tinyInteger('payment_method_type')->comment('1-stripe,2-paypal,3-payment_gateway,4-COD')->nullable();
            $table->tinyInteger('resto_type')->comment('1-Non-Veg,2-Veg,3-Both')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('service_catagories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            //rest attributes
            $table->string('name');
            $table->decimal('commission', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('menu_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of service_catagories table
            $table->integer('service_catagory_id')->nullable()->unsigned();
            $table->foreign('service_catagory_id')->references('id')->on('service_catagories')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('name');
            $table->text('about')->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('resto_menu_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of menu_categories table
            $table->integer('menu_category_id')->unsigned();
            $table->foreign('menu_category_id')->references('id')->on('menu_categories')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of restaurent_details table
            $table->integer('restaurent_id')->nullable()->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('menu_list', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of restaurent_details table
            $table->integer('restaurent_id')->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of menu_categories table
            $table->integer('menu_category_id')->unsigned();
            $table->foreign('menu_category_id')->references('id')->on('resto_menu_categories')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('name');
            $table->string('picture')->nullable();
            $table->text('about')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->tinyInteger('dish_type')->comment('1-Non-Veg,2-Veg')->nullable();
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('menu_customizations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of restaurent_details table
            $table->integer('restaurent_id')->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of menu_categories table
            $table->integer('menu_list_id')->unsigned();
            $table->foreign('menu_list_id')->references('id')->on('menu_list')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('name');
            $table->string('picture')->nullable();
            $table->text('about')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->tinyInteger('customization_type')->comment('1-Non-Veg,2-Veg')->nullable();
            $table->tinyInteger('listing_order')->nullable();
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
        Schema::dropIfExists('menu_customizations');
        Schema::dropIfExists('menu_list');
        Schema::dropIfExists('service_catagories');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('restaurent_details');
    }
}
