<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecommendationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recommendations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('text', 65535)->nullable();
			$table->integer('proposal_id')->nullable()->index('FK_RECOMMENDATION_PROPOSAL');
			$table->integer('person_id')->unsigned()->nullable()->index('FK_RECOMMENDATION');
			$table->dateTime('created_at')->nullable();
			$table->dateTime('update_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recommendations');
	}

}
