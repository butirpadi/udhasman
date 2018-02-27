<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNewPengirimanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('new_pengiriman', function(Blueprint $table)
		{
			$table->foreign('lokasi_galian_id', 'FK_new_pengiriman_lokasi_galian')->references('id')->on('lokasi_galian')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('material_id', 'FK_new_pengiriman_material')->references('id')->on('material')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('pekerjaan_id', 'FK_new_pengiriman_pekerjaan')->references('id')->on('pekerjaan')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('customer_id', 'FK_new_pengiriman_res_partner')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('karyawan_id', 'FK_new_pengiriman_res_partner_2')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('new_pengiriman', function(Blueprint $table)
		{
			$table->dropForeign('FK_new_pengiriman_lokasi_galian');
			$table->dropForeign('FK_new_pengiriman_material');
			$table->dropForeign('FK_new_pengiriman_pekerjaan');
			$table->dropForeign('FK_new_pengiriman_res_partner');
			$table->dropForeign('FK_new_pengiriman_res_partner_2');
		});
	}

}
