<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayrollTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->enum('kategori', array('S','D'))->nullable();
			$table->string('payroll_number', 50)->nullable();
			$table->integer('karyawan_id')->nullable()->index('FK_payroll_karyawan');
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->date('payment_date')->nullable();
			$table->decimal('total', 20)->nullable();
			$table->decimal('potongan_bahan', 20)->nullable();
			$table->decimal('potongan_bon', 20)->nullable();
			$table->decimal('sisa_bayaran', 20)->nullable();
			$table->decimal('dp', 20)->nullable();
			$table->decimal('saldo', 20)->nullable();
			$table->enum('status', array('O','P'))->nullable();
			$table->decimal('daywork', 10)->nullable();
			$table->decimal('basicpay', 20)->nullable();
			$table->integer('user_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll');
	}

}
