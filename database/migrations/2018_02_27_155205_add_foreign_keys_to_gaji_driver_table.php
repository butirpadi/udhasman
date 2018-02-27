<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGajiDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gaji_driver', function(Blueprint $table)
		{
			$table->foreign('partner_id', 'fkey_ocrgzuuaur')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gaji_driver', function(Blueprint $table)
		{
			$table->dropForeign('fkey_ocrgzuuaur');
		});
	}

}
