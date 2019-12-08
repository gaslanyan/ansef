<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('persons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('birthdate')->nullable();
			$table->string('birthplace', 512)->nullable();
			$table->enum('sex', array('male','female','neutral'))->nullable();
			$table->enum('state', array('foreign','domestic'))->nullable();
			$table->string('first_name', 512)->nullable();
			$table->string('last_name', 512)->nullable();
			$table->string('nationality', 512)->nullable();
			$table->enum('type', array('non role','admin','superadmin','referee','viewer','applicant','participant','support'))->nullable();
            $table->string('specialization', 512)->nullable();
			$table->integer('user_id')->unsigned()->nullable();
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
		Schema::drop('persons');
	}

}
