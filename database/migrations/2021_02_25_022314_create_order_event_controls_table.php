<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderEventControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_event_controls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            $table->ingeret('order_id');
            $table->ingeret('user_id');
            $table->ingeret('status')->comment('1.accepted');
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
        Schema::dropIfExists('order_event_controls');
    }
}
