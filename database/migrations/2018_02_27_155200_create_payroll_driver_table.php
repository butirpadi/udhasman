<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayrollDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_driver', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('karyawan_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('payroll_number', 50)->nullable();
			$table->date('payment_date')->nullable();
			$table->date('tanggal_pembayaran')->nullable();
			$table->date('tanggal_awal')->nullable();
			$table->date('tanggal_akhir')->nullable();
			$table->decimal('total', 20)->nullable();
			$table->decimal('potongan_bahan', 20)->nullable();
			$table->decimal('potongan_bon', 20)->nullable();
			$table->decimal('sisa_bayaran', 20)->nullable();
			$table->decimal('dp', 20)->nullable();
			$table->enum('state', array('draft','open','paid'))->nullable()->default('draft');
			$table->decimal('amount_due', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_driver');
	}

}
