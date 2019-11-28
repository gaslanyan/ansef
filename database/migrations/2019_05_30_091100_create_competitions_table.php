<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetitionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('competitions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title', 191)->nullable();
			$table->text('description', 65535)->nullable();
			$table->date('submission_start_date')->nullable();
			$table->date('submission_end_date')->nullable();
			$table->date('announcement_date')->nullable();
			$table->date('project_start_date')->nullable();
			$table->boolean('duration')->nullable();
			$table->integer('min_budget')->nullable();
			$table->integer('max_budget')->nullable();
			$table->integer('min_level_deg_id')->nullable();
			$table->integer('max_level_deg_id')->nullable();
			$table->boolean('min_age')->nullable();
			$table->boolean('max_age')->nullable();
			$table->enum('allow_foreign', array('0','1'))->nullable();
			$table->text('comments', 65535)->nullable();
			$table->date('first_report')->nullable();
			$table->date('second_report')->nullable();
			$table->enum('state', array('enable','disable'))->nullable();
			$table->integer('recommendations_id')->nullable()->default(0);
			$table->text('categories', 65535)->nullable();
			$table->text('additional', 65535)->nullable()->comment('additional_charge_name,additional_charge, additional_percentage_name,additional_percentage');
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
		Schema::drop('competitions');
	}

}
