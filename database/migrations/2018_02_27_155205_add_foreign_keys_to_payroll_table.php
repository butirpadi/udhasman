<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPayrollTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payroll', function(Blueprint $table)
		{
			$table->foreign('karyawan_id', 'FK_payroll_karyawan')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payroll', function(Blueprint $table)
		{
			$table->dropForeign('FK_payroll_karyawan');
		});
	}

}
