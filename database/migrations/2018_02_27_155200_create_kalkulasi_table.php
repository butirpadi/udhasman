<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKalkulasiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kalkulasi', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('pengiriman_id')->nullable();
			$table->string('no_nota_timbang', 50)->nullable();
			$table->enum('status', array('DRAFT','OPEN','VALIDATED','DONE','CANCELED'))->nullable()->default('DRAFT');
			$table->enum('kalkulasi', array('RITASE','KUBIKASI','TONASE'))->nullable()->comment('R:Ritase;K=Kubikase,T=tonase');
			$table->decimal('panjang', 10)->nullable();
			$table->decimal('lebar', 10)->nullable();
			$table->decimal('tinggi', 10)->nullable();
			$table->decimal('volume', 10)->nullable();
			$table->decimal('gross', 10)->nullable();
			$table->decimal('tare', 10)->nullable();
			$table->decimal('netto', 10)->nullable();
			$table->integer('qty')->nullable();
			$table->decimal('unit_price', 20)->nullable();
			$table->decimal('total', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kalkulasi');
	}

}
