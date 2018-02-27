<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGajiDriverPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gaji_driver_payment', function(Blueprint $table)
		{
			$table->foreign('gaji_driver_id', 'fkey_pdywfsjwka_xy')->references('id')->on('gaji_driver')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gaji_driver_payment', function(Blueprint $table)
		{
			$table->dropForeign('fkey_pdywfsjwka_xy');
		});
	}

}
