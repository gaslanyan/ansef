<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstitutionsPersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('institutions_persons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->nullable();
            $table->integer('institution_id')->nullable();
            $table->string('institution', 255)->nullable();
			$table->string('title', 1024);
			$table->date('start')->nullable();
			$table->date('end')->nullable();
			$table->enum('type', array('affiliation','employment'))->nullable();
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
		Schema::drop('institutions_persons');
	}

}
