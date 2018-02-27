<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPiutangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('piutang', function(Blueprint $table)
		{
			$table->foreign('partner_id', 'FK_piutang_res_partner')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('piutang', function(Blueprint $table)
		{
			$table->dropForeign('FK_piutang_res_partner');
		});
	}

}
