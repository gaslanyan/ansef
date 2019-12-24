<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProposalPersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposal_persons', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('person_id');
			$table->integer('proposal_id');
			$table->enum('subtype', array('PI','collaborator','director','supportletter','consultant'));
            $table->integer('competition_id');
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
		Schema::drop('proposal_persons');
	}

}
