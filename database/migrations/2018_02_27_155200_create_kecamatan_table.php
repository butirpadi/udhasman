<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKecamatanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kecamatan', function(Blueprint $table)
		{
			$table->char('id', 7)->primary();
			$table->char('kabupaten_id', 4)->index('districts_regency_id_index');
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
		Schema::drop('kecamatan');
	}

}
