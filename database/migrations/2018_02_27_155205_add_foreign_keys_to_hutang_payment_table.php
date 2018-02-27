<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHutangPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hutang_payment', function(Blueprint $table)
		{
			$table->foreign('hutang_id', 'FK__hutang')->references('id')->on('hutang')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hutang_payment', function(Blueprint $table)
		{
			$table->dropForeign('FK__hutang');
		});
	}

}
