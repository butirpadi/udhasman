<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPembelianDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pembelian_detail', function(Blueprint $table)
		{
			$table->foreign('pembelian_id', 'FK_pembelian_detail_pembelian')->references('id')->on('pembelian')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pembelian_detail', function(Blueprint $table)
		{
			$table->dropForeign('FK_pembelian_detail_pembelian');
		});
	}

}
