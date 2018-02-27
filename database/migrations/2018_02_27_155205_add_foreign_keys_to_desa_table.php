<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDesaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('desa', function(Blueprint $table)
		{
			$table->foreign('kecamatan_id', 'villages_district_id_foreign')->references('id')->on('kecamatan')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('desa', function(Blueprint $table)
		{
			$table->dropForeign('villages_district_id_foreign');
		});
	}

}
