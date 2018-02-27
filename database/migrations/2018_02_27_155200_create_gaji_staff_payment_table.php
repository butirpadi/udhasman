<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGajiStaffPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gaji_staff_payment', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50)->nullable();
			$table->integer('gaji_staff_id')->nullable()->index('fkey_rrvwaykrky');
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->date('tanggal')->nullable();
			$table->decimal('jumlah', 20)->nullable();
			$table->decimal('amount_due', 20)->nullable();
			$table->integer('order')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gaji_staff_payment');
	}

}
