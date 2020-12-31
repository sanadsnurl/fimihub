<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewayTxnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway_txns', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // foreign key of orders table
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->string('txn_id')->unique()->nullable();
            $table->string('token')->nullable();
            $table->tinyInteger('txn_type')->comment('1-Credited,2-Debited');
            $table->decimal('amount', 8, 3)->default(0);
            $table->tinyInteger('status')->comment('1-success,2-failure,3-pending');
            $table->tinyInteger('payment_type')->comment('1-bank_manual,2-paypal,3-net_bank');
            $table->string('comment')->nullable();
            $table->json('bank_response')->nullable();
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
        Schema::dropIfExists('payment_gateway_txns');
    }
}
