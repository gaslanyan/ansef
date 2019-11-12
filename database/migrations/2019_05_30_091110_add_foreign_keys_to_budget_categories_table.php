<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBudgetCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('budget_categories', function(Blueprint $table)
		{
			$table->foreign('competition_id', 'FK_BUDGET_CATEGORIES')->references('id')->on('competitions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('budget_categories', function(Blueprint $table)
		{
			$table->dropForeign('FK_BUDGET_CATEGORIES');
		});
	}

}
