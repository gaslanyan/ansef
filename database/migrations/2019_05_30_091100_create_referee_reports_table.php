<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRefereeReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('referee_reports', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('private_comment', 65535)->nullable();
			$table->text('public_comment', 65535)->nullable();
			$table->enum('state', array('in-progress','rejected','complete'))->nullable();
            $table->integer('proposal_id')->unsigned()->nullable();
            $table->integer('competition_id')->unsigned()->nullable();
			$table->date('due_date')->nullable();
			$table->float('overall_score', 10, 0)->nullable();
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
		Schema::drop('referee_reports');
	}

}
