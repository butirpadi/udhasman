<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGajiDriverDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gaji_driver_detail', function(Blueprint $table)
		{
			$table->foreign('pekerjaan_id', 'fkey_fdqdvabftd')->references('id')->on('pekerjaan')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('gaji_driver_id', 'fkey_opsgnhwhtk')->references('id')->on('gaji_driver')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('material_id', 'fkey_zmfrkomxcg')->references('id')->on('material')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gaji_driver_detail', function(Blueprint $table)
		{
			$table->dropForeign('fkey_fdqdvabftd');
			$table->dropForeign('fkey_opsgnhwhtk');
			$table->dropForeign('fkey_zmfrkomxcg');
		});
	}

}
