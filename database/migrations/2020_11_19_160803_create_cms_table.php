<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            $table->integer('type')->comment('1.About us, 2. term and condition, 3. FAQ, 4.leagal info');
            $table->text('content');
            $table->string('heading')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('slider_cms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->text('text1')->nullable();
            $table->text('text2')->nullable();
            $table->string('link')->nullable();
            $table->string('media')->nullable();
            $table->tinyInteger('slider_type')->default('1')->comment('1-Web Slider,
                                                                2-resto dash,')->nullable();
            $table->integer('listing_order')->nullable();
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
        Schema::dropIfExists('slider_cms');
        Schema::dropIfExists('cms');
    }
}
