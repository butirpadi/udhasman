<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHutangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hutang', function(Blueprint $table)
		{
			$table->foreign('partner_id', 'FK_hutang_res_partner')->references('id')->on('res_partner')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hutang', function(Blueprint $table)
		{
			$table->dropForeign('FK_hutang_res_partner');
		});
	}

}
