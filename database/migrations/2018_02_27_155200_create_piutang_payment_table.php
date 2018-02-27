<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePiutangPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('piutang_payment', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('name', 60)->nullable();
			$table->integer('piutang_id')->nullable();
			$table->decimal('last_amount_due', 20)->nullable();
			$table->decimal('jumlah', 20)->nullable();
			$table->date('tanggal')->nullable();
			$table->integer('payment_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('piutang_payment');
	}

}
