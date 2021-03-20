<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            $table->string('name');
            $table->string('verification_code')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_code')->nullable();
            $table->string('mobile',15)->unique();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password');
            $table->string('picture')->nullable();
            $table->tinyInteger('user_type')->comment('1-Admin,2-Rider,3-User,4-Restaurent')->nullable();
            $table->string('role')->comment('1-rider,2-Resto')->nullable();
            $table->string('device_token')->nullable();
            $table->tinyInteger('status')->default('1')->comment('1-online,0-offline');
            $table->tinyInteger('visibility')->default('0');
            $table->timestamp('deleted_at', 0)->nullable();
            $table->timestamps();
        });

        Schema::create('user_address', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf16';
            $table->collation = 'utf16_general_ci';
            $table->increments('id');
            // foreign key of users table
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            //rest attributes
            $table->text('address');
            $table->string('name');
            $table->string('flat_no');
            $table->string('landmark');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('default_status')->comment('1-default,2-non_default')->default(2);
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
        Schema::dropIfExists('user_address');
        Schema::dropIfExists('users');
    }
}
