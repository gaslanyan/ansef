<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBudgetCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budget_categories', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 191)->nullable();
			$table->integer('min')->nullable();
			$table->integer('max')->nullable();
			$table->integer('weight')->nullable();
			$table->integer('competition_id');
            $table->string('comments', 255)->nullable();
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
		Schema::drop('budget_categories');
	}

}
