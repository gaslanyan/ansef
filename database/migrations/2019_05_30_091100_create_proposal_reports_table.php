<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProposalReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposal_reports', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('description', 65535)->nullable();
			$table->string('document', 191)->nullable();
			$table->integer('proposal_id')->index('FK_PROPOSAL');
			$table->date('due_date')->nullable();
			$table->enum('approved', array('0','1'))->nullable();
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
		Schema::drop('proposal_reports');
	}

}
