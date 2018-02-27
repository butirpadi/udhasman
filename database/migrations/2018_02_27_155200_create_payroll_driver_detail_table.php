<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayrollDriverDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_driver_detail', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('payroll_driver_id')->nullable()->index('fkey_bhhikvofrf');
			$table->integer('pekerjaan_id')->nullable()->index('fkey_yttbfypaoc');
			$table->integer('material_id')->nullable()->index('fkey_vgggszhaul');
			$table->enum('kalkulasi', array('rit','ton','kubik'))->nullable();
			$table->integer('qty')->nullable();
			$table->decimal('volume', 20)->nullable();
			$table->decimal('netto', 20)->nullable();
			$table->decimal('harga_satuan', 20)->nullable();
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
		Schema::drop('payroll_driver_detail');
	}

}
