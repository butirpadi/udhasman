<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToKecamatanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kecamatan', function(Blueprint $table)
		{
			$table->foreign('kabupaten_id', 'districts_regency_id_foreign')->references('id')->on('kabupaten')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kecamatan', function(Blueprint $table)
		{
			$table->dropForeign('districts_regency_id_foreign');
		});
	}

}
