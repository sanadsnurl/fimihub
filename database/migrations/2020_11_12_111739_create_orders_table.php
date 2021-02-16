<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of restaurent_details table
            $table->integer('restaurent_id')->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of user_address table
            $table->integer('address_id')->unsigned()->nullable();
            $table->foreign('address_id')->references('id')->on('user_address')->onDelete('cascade')->onUpdate('cascade');

            $table->string('customer_name')->nullable();
            $table->string('applied_coupon')->nullable();
            $table->decimal('delivery_fee', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('cart_submenus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of carts table
            $table->integer('cart_id')->unsigned();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of menu_list table
            $table->integer('menu_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menu_list')->onDelete('cascade')->onUpdate('cascade');

            $table->string('quantity')->nullable();
            $table->string('product_variant_id')->nullable();
            $table->string('product_add_on_id')->nullable();
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });


        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of users table
            $table->integer('restaurent_id')->unsigned();
            $table->foreign('restaurent_id')->references('id')->on('restaurent_details')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of carts table
            $table->integer('cart_id')->unsigned();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of user_address table
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('user_address')->onDelete('cascade')->onUpdate('cascade');

            $table->string('order_id')->unique();
            $table->string('customer_name')->nullable();
            $table->json('ordered_menu')->nullable();
            $table->string('mobile')->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->decimal('service_tax', 8, 2)->nullable();
            $table->decimal('service_commission', 8, 2)->nullable();
            $table->decimal('delivery_fee', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();

            $table->tinyInteger('order_status')->comment('1-failed,
                                                    2-user_cancel,
                                                    3-pending,
                                                    4-resto_cancel,
                                                    5-placed,
                                                    6-packed,
                                                    7-picked,
                                                    8-rider_cancel,
                                                    9-received,
                                                    10-refunded
                                                    11-assigned to rider
                                                    12-rider on the way')->nullable();
            $table->tinyInteger('payment_status')->comment('1-pending,2-success,3-failed')->nullable();
            $table->tinyInteger('payment_type')->comment('1-bank,2-paypal,3-COD,4-First Atlantic');
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });


        Schema::create('order_events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of orders table
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->tinyInteger('order_status')->comment('based on user type')->nullable();
            $table->string('order_comment')->nullable();
            $table->tinyInteger('order_feedback')->nullable();
            $table->string('feedback_comment')->nullable();
            $table->integer('reason_id')->nullable();
            $table->tinyInteger('user_type')->comment('1-Rider,2-Restaurent')->nullable();
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
        Schema::dropIfExists('order_events');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_submenus');
        Schema::dropIfExists('carts');
    }
}
