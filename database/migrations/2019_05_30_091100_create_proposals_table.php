<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposals', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title', 1024)->nullable();
			$table->text('abstract', 65535)->nullable();
			$table->enum('state', array('in-progress','submitted','in-review','complete','awarded','unsuccessfull','approved 1','approved 2','disqualified','finalist'))->nullable();
			$table->string('document', 512)->nullable();
			$table->float('overall_score', 10, 0)->nullable();
			$table->text('comment', 65535)->nullable();
			$table->integer('rank')->nullable();
			$table->integer('competition_id');
			$table->text('categories', 65535)->nullable()->comment('cat ids from competation table');
			$table->integer('proposal_admin')->nullable();
			$table->string('proposal_referees')->nullable();
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
		Schema::drop('proposals');
	}

}
