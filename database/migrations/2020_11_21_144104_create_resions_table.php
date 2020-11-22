<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            $table->string('resion');
            $table->tinyInteger('user_type')->comment('1-Admin,2-Rider,3-User,4-Restaurent');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('resions');
    }
}
