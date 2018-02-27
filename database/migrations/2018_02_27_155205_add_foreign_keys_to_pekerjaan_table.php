<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPekerjaanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pekerjaan', function(Blueprint $table)
		{
			$table->foreign('partner_id', 'FK_pekerjaan_res_partner')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pekerjaan', function(Blueprint $table)
		{
			$table->dropForeign('FK_pekerjaan_res_partner');
		});
	}

}
