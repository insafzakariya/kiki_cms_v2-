<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	 protected $toTruncate = ['users','activations','role_users'];

	public function run()
	{
		Model::unguard();
		
		foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }
		$this->call('UsersTableSeeder');		

		Model::reguard();
	}

}
