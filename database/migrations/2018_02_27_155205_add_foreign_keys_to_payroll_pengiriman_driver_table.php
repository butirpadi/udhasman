<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPayrollPengirimanDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payroll_pengiriman_driver', function(Blueprint $table)
		{
			$table->foreign('new_pengiriman_id', 'fkey_ussyxzkeaf')->references('id')->on('new_pengiriman')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('payroll_driver_id', 'fkey_vmapcawkhm')->references('id')->on('payroll_driver')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payroll_pengiriman_driver', function(Blueprint $table)
		{
			$table->dropForeign('fkey_ussyxzkeaf');
			$table->dropForeign('fkey_vmapcawkhm');
		});
	}

}
