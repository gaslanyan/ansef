<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBudgetItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budget_item', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('budget_cat_id')->index('FK_BUDGET_CAT');
			$table->text('description', 65535)->nullable();
			$table->integer('amount')->nullable();
			$table->integer('proposal_id')->index('FK_BUDGET_ITEM');
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
		Schema::drop('budget_item');
	}

}
