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
			$table->integer('person_id')->unsigned()->nullable()->index('FK_INSTITUTIONS');
			$table->integer('institution_id')->nullable();
			$table->string('title');
			$table->date('start')->nullable();
			$table->date('end')->nullable();
			$table->enum('type', array('affiliation','employment'))->nullable();
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
