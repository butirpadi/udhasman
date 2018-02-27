<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPresensiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('presensi', function(Blueprint $table)
		{
			$table->foreign('karyawan_id', 'FK_attend_res_partner')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('presensi', function(Blueprint $table)
		{
			$table->dropForeign('FK_attend_res_partner');
		});
	}

}
