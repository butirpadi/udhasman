<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewPengirimanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('new_pengiriman', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->enum('state', array('draft','open','done'))->nullable()->default('draft');
			$table->string('name', 50)->nullable()->comment('Nomor Pengiriman');
			$table->date('order_date')->nullable();
			$table->integer('customer_id')->nullable()->index('FK__customer');
			$table->integer('pekerjaan_id')->nullable()->index('FK_new_pengiriman_pekerjaan');
			$table->integer('karyawan_id')->nullable()->index('FK_new_pengiriman_karyawan');
			$table->string('nopol', 50)->nullable();
			$table->integer('material_id')->nullable()->index('FK_new_pengiriman_material');
			$table->integer('lokasi_galian_id')->nullable()->index('FK_new_pengiriman_lokasi_galian');
			$table->enum('kalkulasi', array('rit','kubik','ton'))->nullable()->default('rit');
			$table->string('nota_timbang', 60)->nullable();
			$table->integer('qty')->nullable()->default(0);
			$table->decimal('panjang', 10)->nullable();
			$table->decimal('lebar', 10)->nullable();
			$table->decimal('tinggi', 10)->nullable();
			$table->decimal('volume', 10)->nullable();
			$table->decimal('gross', 10)->nullable();
			$table->decimal('tare', 10)->nullable();
			$table->decimal('netto', 10)->nullable();
			$table->decimal('harga_satuan', 20)->nullable();
			$table->decimal('harga_total', 20)->nullable();
			$table->enum('invoice_state', array('draft','open','paid'))->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('new_pengiriman');
	}

}
