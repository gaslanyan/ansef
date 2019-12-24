<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDegreePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('degree_persons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->nullable()->index('PK_DEGREES');
			$table->integer('degree_id')->nullable();
            $table->integer('year')->nullable();
            $table->integer('institution_id')->nullable();
            $table->string('institution', 255)->nullable();
            $table->integer('user_id')->nullable();
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
		Schema::drop('degree_persons');
	}

}
