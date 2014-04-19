<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimeEntryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('time_tracking_entry',function($table) {

            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('time_tracking_categories');
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('pay_id')->refernces('id')->on('pay_period');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->time('startTime');
            $table->time('endTime')->nullable();
            $table->text('description')->nullable();
            $table->boolean('clocked_in')->defalut(false);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */

	public function down()
	{

		Schema::drop('time_tracking_entry');

	}

}
