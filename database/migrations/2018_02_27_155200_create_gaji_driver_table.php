<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGajiDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gaji_driver', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('name', 60)->nullable();
			$table->integer('partner_id')->nullable()->index('fkey_ocrgzuuaur');
			$table->date('tanggal_gaji')->nullable();
			$table->date('tanggal_awal')->nullable();
			$table->date('tanggal_akhir')->nullable();
			$table->decimal('jumlah', 20)->nullable();
			$table->enum('state', array('draft','open','paid'))->nullable()->default('draft');
			$table->decimal('gaji_nett', 20)->nullable();
			$table->decimal('amount_due', 20)->nullable();
			$table->string('bulan', 50)->nullable();
			$table->enum('dp', array('Y','N'))->nullable()->default('N');
			$table->decimal('potongan_bahan', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gaji_driver');
	}

}
