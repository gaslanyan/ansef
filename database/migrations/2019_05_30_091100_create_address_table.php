<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('address', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('country_id');
			$table->string('province', 191)->nullable();
			$table->string('street', 512)->nullable();
            $table->integer('addressable_id')->nullable();
            $table->string('addressable_type', 255)->nullable();
            $table->string('city', 255)->nullable();
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
		Schema::drop('address');
	}

}
