<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPayrollDriverDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payroll_driver_detail', function(Blueprint $table)
		{
			$table->foreign('payroll_driver_id', 'fkey_bhhikvofrf')->references('id')->on('payroll_driver')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('material_id', 'fkey_vgggszhaul')->references('id')->on('material')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('pekerjaan_id', 'fkey_yttbfypaoc')->references('id')->on('pekerjaan')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payroll_driver_detail', function(Blueprint $table)
		{
			$table->dropForeign('fkey_bhhikvofrf');
			$table->dropForeign('fkey_vgggszhaul');
			$table->dropForeign('fkey_yttbfypaoc');
		});
	}

}
