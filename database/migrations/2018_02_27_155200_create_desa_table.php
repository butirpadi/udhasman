<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDesaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('desa', function(Blueprint $table)
		{
			$table->char('id', 10)->primary();
			$table->char('kecamatan_id', 7)->index('villages_district_id_index');
			$table->string('name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('desa');
	}

}
