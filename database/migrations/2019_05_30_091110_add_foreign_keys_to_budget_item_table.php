<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBudgetItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('budget_item', function(Blueprint $table)
		{
			$table->foreign('budget_cat_id', 'FK_BUDGET_CAT')->references('id')->on('budget_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('proposal_id', 'FK_BUDGET_ITEM')->references('id')->on('proposals')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('budget_item', function(Blueprint $table)
		{
			$table->dropForeign('FK_BUDGET_CAT');
			$table->dropForeign('FK_BUDGET_ITEM');
		});
	}

}
