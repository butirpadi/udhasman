<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCashbookTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cashbook', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->date('tanggal')->nullable();
			$table->string('desc')->nullable();
			$table->string('cash_number', 50)->nullable();
			$table->decimal('jumlah', 20)->nullable();
			$table->enum('in_out', array('I','O'))->nullable();
			$table->decimal('saldo', 20)->nullable();
			$table->integer('user_id')->nullable();
			$table->enum('state', array('draft','post'))->nullable()->default('draft');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cashbook');
	}

}
