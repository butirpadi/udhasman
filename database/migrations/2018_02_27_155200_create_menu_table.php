<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('menu_id')->nullable();
			$table->string('title', 100)->nullable();
			$table->string('class_request')->nullable();
			$table->string('href')->nullable();
			$table->integer('order')->nullable();
			$table->string('icon', 50)->nullable();
			$table->enum('active', array('Y','N'))->nullable()->default('Y');
			$table->string('access_name', 50)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('menu');
	}

}
