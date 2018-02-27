<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKabupatenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kabupaten', function(Blueprint $table)
		{
			$table->char('id', 4)->primary();
			$table->char('provinsi_id', 2)->index('regencies_province_id_index');
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
		Schema::drop('kabupaten');
	}

}
