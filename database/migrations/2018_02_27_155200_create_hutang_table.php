<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHutangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hutang', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('name', 60)->nullable();
			$table->date('tanggal')->nullable();
			$table->integer('partner_id')->nullable()->index('FK_hutang_res_partner');
			$table->enum('state', array('draft','open','paid'))->nullable()->default('draft');
			$table->string('source', 60)->nullable();
			$table->integer('po_id')->nullable()->comment('id Purchase order');
			$table->string('desc', 250)->nullable();
			$table->enum('type', array('pembelian','lain'))->nullable()->default('pembelian');
			$table->decimal('jumlah', 20)->nullable();
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
		Schema::drop('hutang');
	}

}
