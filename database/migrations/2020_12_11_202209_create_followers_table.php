<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
						$table->increments('id');
						$table->BigInteger('user_id')->unsigned();
						$table->BigInteger('follower_id')->unsigned();
						$table->nullableTimestamps();
						
						$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
						$table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
