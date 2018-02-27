<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppsettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appsetting', function(Blueprint $table)
		{
			$table->string('name', 250)->primary();
			$table->string('value', 500);
			$table->string('desc', 250)->nullable();
			$table->enum('active', array('Y','N'))->nullable()->default('Y');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('appsetting');
	}

}
