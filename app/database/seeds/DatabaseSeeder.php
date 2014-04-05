<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
	}

}

class UserTableSeeder extends Seeder{

	public function run(){
		DB::table('user')->delete();
		User::create(array('username' => 'bob', 'password' => Hash::make('1')));
	}


}


class AvailabilityTableSeeder extends Seeder{

	public function run(){
		DB::table('user')->delete();
		User::create(array('username' => 'bob', 'password' => Hash::make('1')));
	}


}


