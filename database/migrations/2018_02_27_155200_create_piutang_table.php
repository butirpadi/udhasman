<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePiutangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('piutang', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('name', 60)->nullable();
			$table->date('tanggal')->nullable();
			$table->enum('type', array('so','pk','pl'))->nullable()->comment('Piutang SO/Penjualan, piutang Karyawan, Piutang Lain');
			$table->enum('state', array('draft','open','paid'))->nullable()->default('draft');
			$table->string('source', 60)->nullable();
			$table->string('so_id', 60)->nullable();
			$table->integer('partner_id')->nullable()->index('FK_piutang_res_partner');
			$table->string('penerima', 60)->nullable();
			$table->string('desc', 250)->nullable();
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
		Schema::drop('piutang');
	}

}
