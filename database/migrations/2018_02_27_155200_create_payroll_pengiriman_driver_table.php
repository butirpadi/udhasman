<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayrollPengirimanDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_pengiriman_driver', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('payroll_driver_id')->nullable()->index('fkey_vmapcawkhm');
			$table->integer('new_pengiriman_id')->nullable()->index('fkey_ussyxzkeaf');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_pengiriman_driver');
	}

}
