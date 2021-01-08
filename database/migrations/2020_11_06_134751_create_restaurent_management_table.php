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
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->string('pincode')->nullable();
            $table->tinyInteger('payment_method_type')->comment('1-stripe,2-paypal,3-payment_gateway,4-COD')->nullable();
            $table->tinyInteger('resto_type')->comment('1-Non-Veg,2-Veg,3-Both')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });
// service category ie, food,grocery
        Schema::create('service_catagories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            //rest attributes
            $table->string('name');
            $table->decimal('commission', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->decimal('flat_delivery_charge', 8, 2)->nullable();
            $table->tinyInteger('on_km')->nullable();
            $table->decimal('after_flat_delivery_charge', 8, 2)->nullable();
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });
// menu category ie, drinks,main cource (master table)
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
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

//Restaurent  menu category ie, drinks,main cource (Restaurent table)
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
            $table->decimal('discount', 8, 2)->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });
// main menu list
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
            $table->tinyInteger('product_variant_id')->nullable();
            $table->json('product_add_on_id')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->tinyInteger('dish_type')->comment('1-Non-Veg,2-Veg,3-other')->nullable();
            $table->tinyInteger('buy_limit')->nullable();
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });
//customization menu category ie, drinks,main cource (master table)
        Schema::create('custom_menu_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of service_catagories table
            $table->integer('service_catagory_id')->nullable()->unsigned();
            $table->foreign('service_catagory_id')->references('id')->on('service_catagories')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('name');
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

//Restaurent customization menu category ie, drinks,main cource (Restaurent table)
        Schema::create('resto_custom_menu_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of menu_categories table
            $table->integer('custom_cat_id')->unsigned();
            $table->foreign('custom_cat_id')->references('id')->on('custom_menu_categories')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of restaurent_details table
            $table->integer('restaurent_id')->nullable()->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->tinyInteger('customization_variant')->comment('1-Add-On,2-Product Variation')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });
// menu Add-On ie -fries,cheese
        Schema::create('menu_customizations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of resto_custom_menu_categories table
            $table->integer('resto_custom_id')->unsigned();
            $table->foreign('resto_custom_id')->references('id')->on('resto_custom_menu_categories')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of restaurent_details table
            $table->integer('restaurent_id')->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of menu_categories table
            $table->integer('menu_list_id')->unsigned();
            $table->foreign('menu_list_id')->references('id')->on('menu_list')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('name');
            $table->string('picture')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->tinyInteger('customization_type')->comment('1-Non-Veg,2-Veg,3-other')->nullable();
            $table->tinyInteger('buy_limit')->nullable();
            $table->tinyInteger('listing_order')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });


//customization main menu list
    Schema::create('menu_custom_list', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->charset = 'utf16';
        $table->collation = 'utf16_general_ci';
        $table->increments('id');
        // foreign key of resto_custom_menu_categories table
        $table->integer('resto_custom_cat_id')->unsigned();
        $table->foreign('resto_custom_cat_id')->references('id')->on('resto_custom_menu_categories')->onDelete('cascade')->onUpdate('cascade');
        // foreign key of restaurent_details table
        $table->integer('restaurent_id')->unsigned();
        $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
        //rest attributes
        $table->string('name');
        $table->decimal('price', 8, 2)->nullable();
        $table->tinyInteger('dish_type')->comment('1-Non-Veg,2-Veg,3-other')->nullable();
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
        Schema::dropIfExists('menu_custom_list');
        Schema::dropIfExists('resto_custom_menu_categories');
        Schema::dropIfExists('custom_menu_categories');
        Schema::dropIfExists('menu_customizations');
        Schema::dropIfExists('resto_menu_categories');
        Schema::dropIfExists('menu_list');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('service_catagories');
        Schema::dropIfExists('restaurent_details');
    }
}
