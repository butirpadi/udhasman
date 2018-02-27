<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayrollStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_staff', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('payroll_number', 50)->nullable();
			$table->date('payment_date')->nullable();
			$table->integer('karyawan_id')->nullable();
			$table->decimal('basic_pay', 20)->nullable();
			$table->integer('total_pagi')->nullable();
			$table->integer('total_siang')->nullable();
			$table->decimal('potongan', 20)->nullable();
			$table->integer('daywork')->nullable();
			$table->decimal('netpay', 20)->nullable();
			$table->enum('status', array('D','O','P'))->nullable()->default('D')->comment('Draft, Open, Paid');
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
		Schema::drop('payroll_staff');
	}

}
