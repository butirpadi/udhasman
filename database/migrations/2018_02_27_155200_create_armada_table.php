<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArmadaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('armada', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamps();
			$table->string('kode', 50)->nullable();
			$table->string('nama')->nullable();
			$table->string('nopol', 50)->nullable();
			$table->integer('karyawan_id')->nullable();
			$table->integer('user_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('armada');
	}

}
