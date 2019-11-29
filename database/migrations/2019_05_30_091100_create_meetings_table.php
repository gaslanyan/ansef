<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->nullable()->index('PK_MEETINGS');
			$table->string('description', 1024)->nullable();
			$table->integer('year')->nullable();
			$table->enum('ansef_supported', array('0','1'))->nullable();
			$table->enum('domestic', array('0','1'))->nullable();
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
		Schema::drop('meetings');
	}

}
