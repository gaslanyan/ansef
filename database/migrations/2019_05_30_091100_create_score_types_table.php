<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScoreTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('score_types', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 191)->nullable();
			$table->text('description', 65535)->nullable();
			$table->boolean('min')->nullable();
			$table->boolean('max')->nullable();
			$table->float('weight', 10, 0)->nullable();
			$table->integer('competition_id')->index('FK_SCORE_TYPES');
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
		Schema::drop('score_types');
	}

}
