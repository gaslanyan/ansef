<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHonorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('honors', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('description', 65535)->nullable();
			$table->integer('year')->nullable();
			$table->integer('person_id')->unsigned();
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
		Schema::drop('honors');
	}

}
