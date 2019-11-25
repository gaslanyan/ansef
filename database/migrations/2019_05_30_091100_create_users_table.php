<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 191);
			$table->string('password', 191)->nullable();
			$table->string('password_salt', 2)->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->boolean('role_id')->nullable()->default(1);
			$table->boolean('requested_role_id')->nullable()->default(0);
			$table->string('confirmation', 191)->nullable();
			$table->enum('state', array('active','inactive','disabled','waiting'))->nullable()->default('inactive');
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
		Schema::drop('users');
	}

}
