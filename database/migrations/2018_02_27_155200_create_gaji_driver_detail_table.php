<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGajiDriverDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gaji_driver_detail', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('gaji_driver_id')->nullable()->index('fkey_opsgnhwhtk');
			$table->integer('material_id')->nullable()->index('fkey_zmfrkomxcg');
			$table->integer('pekerjaan_id')->nullable()->index('fkey_fdqdvabftd');
			$table->enum('kalkulasi', array('rit','kubik','ton'))->nullable();
			$table->integer('rit')->nullable();
			$table->decimal('volume', 20)->nullable();
			$table->decimal('netto', 20)->nullable();
			$table->decimal('harga', 20)->nullable();
			$table->decimal('jumlah', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gaji_driver_detail');
	}

}
