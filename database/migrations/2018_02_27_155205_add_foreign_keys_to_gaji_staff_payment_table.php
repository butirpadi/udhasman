<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGajiStaffPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gaji_staff_payment', function(Blueprint $table)
		{
			$table->foreign('gaji_staff_id', 'fkey_rrvwaykrky')->references('id')->on('gaji_staff')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gaji_staff_payment', function(Blueprint $table)
		{
			$table->dropForeign('fkey_rrvwaykrky');
		});
	}

}
