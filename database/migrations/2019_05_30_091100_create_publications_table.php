<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePublicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('publications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->nullable()->index('FK_PUBLICATIONS');
			$table->string('journal', 191)->nullable();
			$table->text('title', 65535)->nullable();
			$table->date('year')->nullable();
			$table->enum('domestic', array('0','1'))->nullable();
			$table->enum('ansef_supported', array('0','1'))->nullable();
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
		Schema::drop('publications');
	}

}
