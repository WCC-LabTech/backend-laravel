<?php

use Illuminate\Database\Migrations\Migration;

class UserTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		
		Schema::create('user', function($table){
			$table->increments('id');

			$table->string('email', 50)->unique();
			$table->string('first_name', 30);
			$table->string('last_name', 30);
			$table->string('password', 128);
			$table->integer('prefered_hours');
			$table->integer('working_hours');
			$table->enum('department', array('bos', 'none'))->default('none');
			$table->boolean('admin')->default(false);
			$table->enum('type', array('labAide','fullTime','partTime', 'adjunct', 'labTech', 'other'))->default('other');
			$table->string('username', 40)->unique();
			$table->string('reset_token', 40)->nullable();
			$table->string('remember_token', 100)->nullable();

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
		Schema::drop('user');
	}

}