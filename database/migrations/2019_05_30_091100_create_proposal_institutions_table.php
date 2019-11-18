<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProposalInstitutionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposal_institutions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('institution_id')->nullable();
            $table->integer('proposal_id')->nullable();
            $table->string('institutionname')->nullable();
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
		Schema::drop('proposal_institutions');
	}

}
