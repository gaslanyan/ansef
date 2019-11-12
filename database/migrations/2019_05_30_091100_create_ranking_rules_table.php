<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRankingRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ranking_rules', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('sql', 65535)->nullable();
			$table->integer('value')->nullable();
			$table->integer('competition_id')->index('FK_RANKING_RULES');
			$table->integer('user_id')->unsigned();
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
		Schema::drop('ranking_rules');
	}

}
