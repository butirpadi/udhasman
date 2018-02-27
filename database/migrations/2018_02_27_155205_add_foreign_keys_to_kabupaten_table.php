<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToKabupatenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kabupaten', function(Blueprint $table)
		{
			$table->foreign('provinsi_id', 'regencies_province_id_foreign')->references('id')->on('provinsi')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kabupaten', function(Blueprint $table)
		{
			$table->dropForeign('regencies_province_id_foreign');
		});
	}

}
