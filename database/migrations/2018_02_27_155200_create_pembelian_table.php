<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePembelianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pembelian', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('supplier_ref', 50)->nullable();
			$table->string('ref', 50)->nullable();
			$table->date('tanggal')->nullable();
			$table->integer('supplier_id')->nullable()->index('FK_pembelian_res_partner');
			$table->decimal('subtotal', 20)->nullable();
			$table->decimal('disc', 20)->nullable();
			$table->decimal('total', 20)->nullable();
			$table->integer('user_id')->nullable();
			$table->enum('state', array('draft','done'))->nullable()->default('draft');
			$table->enum('bill_state', array('draft','open','paid'))->nullable()->comment('Draft, Open, Validated, Paid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pembelian');
	}

}
