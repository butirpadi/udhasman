<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 60)->default('Draft Payment');
			$table->date('tanggal')->nullable();
			$table->enum('type', array('so','pk','pl'))->nullable();
			$table->integer('partner_id')->nullable()->index('FK_payment_res_partner');
			$table->decimal('jumlah', 20)->nullable();
			$table->decimal('residual', 20)->nullable();
			$table->enum('state', array('draft','post','rec'))->nullable();
			$table->string('memo', 250)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment');
	}

}
